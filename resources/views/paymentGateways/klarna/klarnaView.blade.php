@php
    $stripeKey = "";
    $currency = "USD";
    $orderId = 0;
    $amount = 0;
    
    if (!blank($paymentGateways ?? null)) {
        foreach ($paymentGateways as $gateway) {
            if ($gateway->slug === 'stripe') {
                $paymentGatewayOption = $gateway->gatewayOptions->pluck('value', 'option');
                $stripeKey = $paymentGatewayOption['stripe_key'];
            }
        }
    }
    
    if (isset($order)) {
        $orderId = $order->id;
        $amount = $order->total;
        
        if (isset($currency) && is_object($currency)) {
            $currency = $currency->code;
        }
    }
@endphp

<!-- Hidden fields for Klarna payment -->
<input type="hidden" id="stripe-key" value="{{ $stripeKey }}">
<input type="hidden" id="order-id" value="{{ $orderId }}">
<input type="hidden" id="currency-code" value="{{ $currency }}">
<input type="hidden" id="total-amount" value="{{ $amount }}">

<!-- Card Element for Stripe -->
<div id="card-element-container" class="mt-4 mb-4 hidden">
    <div id="card-element" class="p-4 border rounded-lg"></div>
    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
</div>

<!-- Express Checkout Element -->
<div id="express-checkout-element" class="my-4 hidden"></div>

