@php $stripeKey = ""; @endphp
@if (!blank($paymentGateways))
    @foreach ($paymentGateways as $gateway)
        @if ($gateway->slug === 'stripe')
            @php
                $paymentGatewayOption = $gateway->gatewayOptions->pluck('value', 'option');
                $stripeKey = $paymentGatewayOption['stripe_key'];
            @endphp
        @endif
    @endforeach
@endif

<script>
    const klarnaEnabled = '<?= isset($paymentGateways) && $paymentGateways->where("slug", "klarna")->where("status", 5)->count() > 0 ? "true" : "false" ?>';
    const stripeKey = '<?= $stripeKey ?>';
    const klarnaTotalAmount = '<?= isset($order) ? $order->total : 0 ?>';
    const klarnaCurrencyCode = '<?= isset($currency) ? (is_object($currency) ? $currency->code : $currency) : "USD" ?>';
    const klarnaExpressPayLink = '<?= isset($order) ? route('payment.store', ['order' => $order]) : "" ?>';
    const klarnaSuccessLink = '<?= isset($order) ? route('payment.success', ['order' => $order, 'paymentGateway' => 'klarna']) : "" ?>';
    const klarnaCancelLink = '<?= isset($order) ? route('payment.cancel', ['order' => $order, 'paymentGateway' => 'klarna']) : "" ?>';
    const klarnaButtonText = '<?= trans('all.label.pay_with_klarna') ?>';
</script>
<script src="{{ asset('paymentGateways/klarna/klarna.js') }}"></script>

<script>
// Get Stripe key and order details
const klarnaStripeKey = document.getElementById('stripe-key').value;
const klarnaOrderId = document.getElementById('order-id').value;
const klarnaCurrency = document.getElementById('currency-code').value;
const klarnaAmount = document.getElementById('total-amount').value;
const klarnaPayButtonText = "{{ __('all.label.pay_with_klarna') }}";

// Initialize Stripe
if (klarnaStripeKey) {
    const stripe = Stripe(klarnaStripeKey);
    const cardElements = stripe.elements();
    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            border: '1px solid red',
        },
    };

    // Create card element
    const card = cardElements.create('card', { style: style });
    
    // Show card element when card button is clicked
    document.getElementById('card-checkout-btn').addEventListener('click', function() {
        document.getElementById('card-element-container').classList.remove('hidden');
        document.getElementById('express-checkout-element').classList.add('hidden');
        card.mount('#card-element');
        document.getElementById('confirmBtn').classList.remove('hidden');
    });

    // Handle form submission for card payments
    document.getElementById('paymentForm').addEventListener('submit', function(event) {
        if (document.getElementById('card-element-container').classList.contains('hidden')) {
            return; // Not using card payment, let form submit normally
        }
        
        event.preventDefault();
        
        // Show loading state
        document.getElementById('confirmBtn').disabled = true;
        document.getElementById('confirmBtn').classList.add('opacity-50');
        
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Show error to customer
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                document.getElementById('confirmBtn').disabled = false;
                document.getElementById('confirmBtn').classList.remove('opacity-50');
            } else {
                // Send token to server
                const form = document.getElementById('paymentForm');
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });

    // Express checkout with Klarna
    const orderData = {
        currency: klarnaCurrency.toLowerCase(),
        amount: parseFloat(klarnaAmount) * 100,
    };

    // Check if currency is supported by Klarna
    const supportedKlarnaCurrencies = ['eur', 'dkk', 'gbp', 'nok', 'sek', 'usd', 'czk', 'ron', 'aud', 'nzd', 'cad', 'pln', 'chf'];
    const isKlarnaSupported = supportedKlarnaCurrencies.includes(klarnaCurrency.toLowerCase());
    
    // Define payment methods for express checkout
    const expressCheckoutOptions = {
        paymentMethods: {
            card: 'always',
            applePay: 'auto',
            googlePay: 'auto',
            link: 'auto',
            klarna: isKlarnaSupported ? 'always' : 'never'
        },
        paymentMethodOrder: ['card', 'googlePay', 'applePay', 'link', 'klarna'],
        buttonType: {
            applePay: 'buy',
            googlePay: 'order',
        }
    };
    
    // Create express checkout element
    const elements = stripe.elements({
        locale: 'en',
        mode: 'payment',
        amount: parseFloat(klarnaAmount) * 100,
        currency: klarnaCurrency.toLowerCase(),
    });
    
    const expressCheckoutElement = elements.create('expressCheckout', expressCheckoutOptions);
    
    // Handle Klarna checkout button click
    document.getElementById('klarna-checkout-btn').addEventListener('click', function() {
        // Show loading state
        const originalHTML = this.innerHTML;
        this.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
        this.style.opacity = '0.7';
        
        // Send request to create a Stripe checkout session for Klarna
        fetch('/payment/' + klarnaOrderId + '/pay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order: orderData,
                paymentMethod: 'klarna',
                stripeToken: 'express',
                use_checkout: 'true',
                klarna_only: 'true'
            })
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (data.sessionId) {
                // Redirect to Stripe Checkout
                return stripe.redirectToCheckout({
                    sessionId: data.sessionId
                });
            } else {
                throw new Error('Missing sessionId');
            }
        })
        .then(function(result) {
            if (result.error) {
                throw new Error(result.error.message);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            // Reset button
            document.getElementById('klarna-checkout-btn').innerHTML = originalHTML;
            document.getElementById('klarna-checkout-btn').style.opacity = '1';
            alert('Payment failed: ' + error.message);
        });
    });
    
    // Show express checkout when express button is clicked
    document.getElementById('express-checkout-btn')?.addEventListener('click', function() {
        document.getElementById('express-checkout-element').classList.remove('hidden');
        document.getElementById('card-element-container').classList.add('hidden');
        expressCheckoutElement.mount('#express-checkout-element');
    });
    
    // Handle express checkout confirm
    expressCheckoutElement.on('confirm', function(event) {
        // Send payment data to server
        fetch('/payment/' + klarnaOrderId + '/pay', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                order: orderData,
                paymentMethod: event.paymentMethod.type === 'klarna' ? 'klarna' : 'stripe',
                stripeToken: 'express',
                payment_intent: event.paymentIntent.id
            })
        })
        .then(function(response) {
            if (response.ok) {
                return response.json();
            }
            return Promise.reject(response);
        })
        .then(function(data) {
            event.complete('success');
            window.location.href = data.redirect;
        })
        .catch(function(error) {
            console.error('Error:', error);
            event.complete('fail');
        });
    });
}
</script> 