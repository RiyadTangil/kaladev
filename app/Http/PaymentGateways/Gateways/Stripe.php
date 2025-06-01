<?php

namespace App\Http\PaymentGateways\Gateways;

use Exception;
use Stripe\Charge;
use App\Enums\Activity;
use App\Models\Currency;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe as StripeClient;
use App\Models\PaymentGateway;
use App\Services\PaymentService;
use App\Services\PaymentAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;
use App\Models\CapturePaymentNotification;

class Stripe extends PaymentAbstract
{

    public bool $response = false;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);

        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'stripe'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = $this->paymentGateway->gatewayOptions->pluck('value', 'option');
            $this->gateway              = new StripeClient\StripeClient($this->paymentGatewayOption['stripe_secret']);
        }
        \Stripe\Stripe::setApiKey($this->paymentGatewayOption['stripe_secret']);
    }

    public function payment($order, $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            if ($request->stripeToken === "express") {
                $paymentMethodTypes = ['card', 'link', 'klarna'];  // Always include Klarna
                
                // Add Klarna to payment methods if supported in the currency
                $supportedKlarnaCurrencies = ['eur', 'dkk', 'gbp', 'nok', 'sek', 'usd', 'czk', 'ron', 'aud', 'nzd', 'cad', 'pln', 'chf'];
                $currencyLower = strtolower($request->order['currency']);
                

                
             
                // Check if we should use Checkout Session instead
                if ($request->use_checkout === 'true') {
                    // Create a checkout session with Klarna support
                    $session = \Stripe\Checkout\Session::create([
                        'payment_method_types' => $request->klarna_only === 'true' ? ['klarna'] : $paymentMethodTypes,
                        'line_items' => [[
                            'price_data' => [
                                'currency' => $currencyLower,
                                'product_data' => [
                                    'name' => 'Order #' . $order->id,
                                ],
                                'unit_amount' => (int)($order->total * 100),
                            ],
                            'quantity' => 1,
                        ]],
                        'mode' => 'payment',
                        'success_url' => route('payment.success', ['paymentGateway' => 'stripe', 'order' => $order]) . '?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => route('payment.cancel', ['paymentGateway' => 'stripe', 'order' => $order]),
                    ]);
                    
                
                    return response()->json([
                        'sessionId' => $session->id
                    ]);
                }
                
                // Otherwise use Payment Intent
                $paymentIntent = PaymentIntent::create([
                    'amount' => $request->order['amount'],
                    'currency' => $request->order['currency'],
                    'payment_method_types' => $paymentMethodTypes,
                ]);
                
          
                return response()->json([
                    'clientSecret' => $paymentIntent['client_secret'],
                    'paymentMethodTypes' => $paymentMethodTypes,
                ]);
            }
            
            if ($request->stripeToken !== "express") {
                $currencyCode = 'USD';
                $currencyId   = Settings::group('site')->get('site_default_currency');
                if (!blank($currencyId)) {
                    $currency = Currency::find($currencyId);
                    if ($currency) {
                        $currencyCode = $currency->code;
                    }
                }

                $response = $this->gateway->charges->create([
                    'amount'      => (int) $order->total * 100,
                    'currency'    => $currencyCode,
                    'source'      => $request->stripeToken,
                    'description' => 'Food order payment',
                ]);

                if (isset($response->status) && $response->status == 'succeeded') {
                    $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                        ['order_id', $order->id]
                    ]);
                    $capturePaymentNotification?->delete();
                    $token = $response->balance_transaction;
                    CapturePaymentNotification::create([
                        'order_id'   => $order->id,
                        'token'      => $token,
                        'created_at' => now()
                    ]);
                    return redirect()->away(
                        route('payment.success', ['paymentGateway' => 'stripe', 'order' => $order, 'token' => $token])
                    );
                }
            }
            
            // Default fallback return if no conditions are met
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'stripe'])->with(
                'error',
                trans('all.message.something_wrong')
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'stripe'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'stripe', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($order, $request) {
                $retryLimit = 5;
                $retryCount = 0;
                
                // Handle Checkout Session
                if ($request->session_id) {
                    $session = \Stripe\Checkout\Session::retrieve($request->session_id);
                    $paymentIntent = PaymentIntent::retrieve($session->payment_intent);
                    $paymentMethod = PaymentMethod::retrieve($paymentIntent->payment_method);
                    $charge = Charge::retrieve($paymentIntent->latest_charge);
                    
                    while (is_null($charge['balance_transaction']) && $retryCount < $retryLimit) {
                        sleep(1); // Wait for 1 seconds before retrying
                        $charge = Charge::retrieve($paymentIntent->latest_charge);
                        $retryCount++;
                    }
                    
                    $this->paymentService->payment($order, 'stripe', ($paymentMethod->type . '-' . $charge->balance_transaction));
                    $this->response = true;
                }
                // Handle Payment Intent
                else if (!$request->token && $request['payment_intent'] && $request['redirect_status'] == 'succeeded') {
                    $paymentIntent = PaymentIntent::retrieve($request['payment_intent']);
                    $paymentMethod = PaymentMethod::retrieve($paymentIntent['payment_method']);
                    $charge = Charge::retrieve($paymentIntent['latest_charge']);
                    while (is_null($charge['balance_transaction']) && $retryCount < $retryLimit) {
                        sleep(1); // Wait for 1 seconds before retrying
                        $charge = Charge::retrieve($paymentIntent['latest_charge']);
                        $retryCount++;
                    }
                    $this->paymentService->payment($order, 'stripe', ($paymentMethod['type'] . '-' . $charge['balance_transaction']));
                    $this->response = true;
                }
                // Handle token
                else if ($request->token) {
                    $capturePaymentNotification = DB::table('capture_payment_notifications')->where([
                        ['token', $request->token]
                    ]);
                    $token = $capturePaymentNotification->first();
                    if (!blank($token) && $order->id == $token->order_id) {
                        $this->paymentService->payment($order, 'stripe', $token->token);
                        $capturePaymentNotification->delete();
                        $this->response = true;
                    }
                }
            });

            if ($this->response) {
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            }
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'stripe'])->with(
                'error',
                trans('all.message.something_wrong')
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'stripe'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'stripe'])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
}
