<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $company['company_name'] ?? 'Checkout' }}</title>
    <link rel="icon" href="{{ $faviconLogo->faviconLogo ?? '' }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
</head>

<body>
    <div class="py-14 px-4 w-full max-w-3xl mx-auto">
        <div id="checkout-app"></div>
        
        <!-- Stripe Checkout with Klarna Button -->
        <div class="py-3 w-full rounded-3xl text-center text-base font-medium bg-[#ffb3c7] hover:bg-[#f082a1] text-black cursor-pointer mb-4 flex items-center justify-center shadow-md transition-all duration-200 hidden" id="klarna-checkout-btn">
            <!-- Klarna Logo SVG -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                <path d="M12 6C9.79086 6 8 7.79086 8 10C8 12.2091 9.79086 14 12 14C14.2091 14 16 12.2091 16 10C16 7.79086 14.2091 6 12 6Z" fill="black"/>
                <path d="M8 16V18H16V16H8Z" fill="black"/>
            </svg>
            {{ __('all.label.pay_with_klarna') }}
        </div>
        
        <!-- Hidden fields for Klarna payment -->
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
                
                if (isset($currency)) {
                    $currency = $currency->code;
                }
            }
        @endphp
        
        <input type="hidden" id="stripe-key" value="{{ $stripeKey }}">
        <input type="hidden" id="order-id" value="{{ $orderId }}">
        <input type="hidden" id="currency-code" value="{{ $currency }}">
        <input type="hidden" id="total-amount" value="{{ $amount }}">
    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('lib/jquery-v3.2.1.min.js') }}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('paymentGateways/klarna/klarna.js') }}"></script>
    <script>
        // Hide Klarna button initially - will be shown when needed
        document.addEventListener('DOMContentLoaded', function() {
            $('#klarna-checkout-btn').addClass('hidden');
            
            // Listen for payment method selection changes
            document.addEventListener('paymentMethodSelected', function(e) {
                if (e.detail && e.detail.slug === 'stripe') {
                    $('#klarna-checkout-btn').removeClass('hidden');
                } else if (e.detail && e.detail.slug === 'klarna') {
                    $('#klarna-checkout-btn').removeClass('hidden');
                } else {
                    $('#klarna-checkout-btn').addClass('hidden');
                }
            });
        });
    </script>
</body>

</html> 