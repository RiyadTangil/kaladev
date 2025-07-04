// Klarna payment handler using Stripe
document.addEventListener('DOMContentLoaded', function() {
    const klarnaButton = document.getElementById('klarna-checkout-btn');
    
    if (klarnaButton) {
        klarnaButton.addEventListener('click', function() {
            // Show loading state
            const originalHTML = klarnaButton.innerHTML;
            klarnaButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
            klarnaButton.style.opacity = '0.7';
            
            // Get Stripe key and order details from the page
            let stripeKey = document.getElementById('stripe-key').value;
            let orderId = document.getElementById('order-id').value;
            let currency = document.getElementById('currency-code').value;
            let amount = document.getElementById('total-amount').value;
            
            if (!stripeKey) {
                console.error('Stripe key not found');
                return;
            }
            
            const stripe = Stripe(stripeKey);
            
            // Prepare the order data
            const orderData = {
                currency: currency.toLowerCase(),
                amount: parseFloat(amount) * 100,
            };
            
            // Use the format: /payment/{order}/pay instead of /payment/stripe/{order}/pay
            fetch('/payment/' + orderId + '/pay', {
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
                klarnaButton.innerHTML = originalHTML;
                klarnaButton.style.opacity = '1';
                alert('Payment failed: ' + error.message);
            });
        });
    }
}); 