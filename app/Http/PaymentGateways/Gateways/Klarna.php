<?php

namespace App\Http\PaymentGateways\Gateways;

use App\Enums\Activity;
use App\Models\PaymentGateway;
use App\Services\PaymentAbstract;
use App\Services\PaymentService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Currency;
use App\Models\ThemeSetting;
use Smartisan\Settings\Facades\Settings;

class Klarna extends PaymentAbstract
{
    public bool $response = false;
    protected $stripeGateway;
    public object $paymentGateway;
    public object $paymentGatewayOption;

    public function __construct()
    {
        $paymentService = new PaymentService();
        parent::__construct($paymentService);

        // Initialize Stripe gateway as Klarna payments go through Stripe
        $this->stripeGateway = new \App\Http\PaymentGateways\Gateways\Stripe();
        
        // Get Klarna payment gateway settings
        $this->paymentGateway = PaymentGateway::with('gatewayOptions')->where(['slug' => 'klarna'])->first();
        if (!blank($this->paymentGateway)) {
            $this->paymentGatewayOption = (object) $this->paymentGateway->gatewayOptions->pluck('value', 'option')->toArray();
        }
    }

    public function payment($order, $request): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        // Forward to Stripe gateway for actual payment processing
        // but keep the paymentMethod as klarna
        $request->merge(['paymentMethod' => 'klarna']);
        
        return $this->stripeGateway->payment($order, $request);
    }

    public function status(): bool
    {
        $paymentGateways = PaymentGateway::where(['slug' => 'klarna', 'status' => Activity::ENABLE])->first();
        if ($paymentGateways) {
            return true;
        }
        return false;
    }
    
    public function index($order, $request)
    {
        $credit = false;
        $cashOnDelivery = true;
        $paymentGateways = PaymentGateway::with('gatewayOptions')->where(['status' => Activity::ENABLE])->get();
        $company = Settings::group('company')->all();
        $logo = ThemeSetting::where(['key' => 'theme_frontend_logo'])->first();
        $faviconLogo = ThemeSetting::where(['key' => 'theme_favicon_logo'])->first();
        $currency = Currency::findOrFail(Settings::group('site')->get('site_default_currency'));
        
        if ($order?->user?->balance >= $order->total) {
            $credit = true;
        }

        if (blank($order->transaction)) {
            return view('payment', [
                'company' => $company,
                'logo' => $logo,
                'currency' => $currency,
                'faviconLogo' => $faviconLogo,
                'paymentGateways' => $paymentGateways,
                'order' => $order,
                'creditAmount' => app('App\Libraries\AppLibrary')->currencyAmountFormat($order->user?->balance),
                'credit' => $credit,
                'cashOnDelivery' => $cashOnDelivery,
                'paymentMethod' => $this->paymentGateway
            ]);
        }
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }

    public function success($order, $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::transaction(function () use ($order, $request) {
                // Handle Checkout Session
                if ($request->session_id) {
                    // Use Stripe to process the success and mark payment as klarna
                    $session = \Stripe\Checkout\Session::retrieve($request->session_id);
                    $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
                    $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentIntent->payment_method);
                    $charge = \Stripe\Charge::retrieve($paymentIntent->latest_charge);
                    
                    $retryLimit = 5;
                    $retryCount = 0;
                    while (is_null($charge['balance_transaction']) && $retryCount < $retryLimit) {
                        sleep(1); // Wait for 1 seconds before retrying
                        $charge = \Stripe\Charge::retrieve($paymentIntent->latest_charge);
                        $retryCount++;
                    }
                    
                    $this->paymentService->payment($order, 'klarna', ('klarna-' . $charge->balance_transaction));
                    $this->response = true;
                }
            });

            if ($this->response) {
                return redirect()->route('payment.successful', ['order' => $order])->with(
                    'success',
                    trans('all.message.payment_successful')
                );
            }
            
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'klarna'])->with(
                'error',
                trans('all.message.something_wrong')
            );
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return redirect()->route('payment.fail', ['order' => $order, 'paymentGateway' => 'klarna'])->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function fail($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('payment.index', ['order' => $order, 'paymentGateway' => 'klarna'])->with(
            'error',
            trans('all.message.something_wrong')
        );
    }

    public function cancel($order, $request): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('home')->with('error', trans('all.message.payment_canceled'));
    }
} 