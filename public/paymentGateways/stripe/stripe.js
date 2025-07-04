if (stripeKey) {
    var stripe = Stripe(stripeKey);
    var cardElements = stripe.elements();
    var style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            border: '1px solid red',
        },
    };

    var card = cardElements.create('card', { style: style });
    card.mount('#card-element');

    function stripe_payment() {
        $('#payment_method').parent().removeClass('has-error');
        stripe.createToken(card).then(function (result) {
            if (result.error) {
                let errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    }

    function stripeTokenHandler(token) {
        let form = document.getElementById('paymentForm');
        let hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        form.submit();
    }

    // Stripe Checkout with Klarna support
    const checkoutButton = document.getElementById('stripe-checkout-btn');
    const confirmButton = document.getElementById('confirmBtn');
    
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            // Disable confirm button
            if (confirmButton) {
                confirmButton.disabled = true;
                confirmButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            // Show loading state
            const originalHTML = checkoutButton.innerHTML;
            checkoutButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
            checkoutButton.style.opacity = '0.7';
            
            const orderData = {
                mode: 'payment',
                amount: stripeTotalAmount * 100,
                currency: stripeCurrencyCode.toLowerCase(),
            };
            
            // Request the checkout session
            fetch(stripeExpressPayLink, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    order: orderData,
                    paymentMethod: 'stripe',
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
                checkoutButton.textContent = 'Error: ' + error.message;
                checkoutButton.style.opacity = '1';
                checkoutButton.classList.remove('bg-[#ffb3c7]', 'hover:bg-[#f082a1]', 'text-black');
                checkoutButton.classList.add('bg-red-600', 'hover:bg-red-700', 'text-white');
                setTimeout(function() {
                    // Reset button to original state
                    checkoutButton.classList.remove('bg-red-600', 'hover:bg-red-700', 'text-white');
                    checkoutButton.classList.add('bg-[#ffb3c7]', 'hover:bg-[#f082a1]', 'text-black');
                    
                    // Re-add the Klarna logo and text
                    const klarnaLogo = document.createElement('svg');
                    klarnaLogo.setAttribute('width', '24');
                    klarnaLogo.setAttribute('height', '24');
                    klarnaLogo.setAttribute('viewBox', '0 0 24 24');
                    klarnaLogo.setAttribute('fill', 'none');
                    klarnaLogo.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                    klarnaLogo.classList.add('mr-2');
                    
                    const path1 = document.createElement('path');
                    path1.setAttribute('d', 'M12 6C9.79086 6 8 7.79086 8 10C8 12.2091 9.79086 14 12 14C14.2091 14 16 12.2091 16 10C16 7.79086 14.2091 6 12 6Z');
                    path1.setAttribute('fill', 'black');
                    
                    const path2 = document.createElement('path');
                    path2.setAttribute('d', 'M8 16V18H16V16H8Z');
                    path2.setAttribute('fill', 'black');
                    
                    klarnaLogo.appendChild(path1);
                    klarnaLogo.appendChild(path2);
                    
                    checkoutButton.innerHTML = '';
                    checkoutButton.appendChild(klarnaLogo);
                    checkoutButton.appendChild(document.createTextNode(klarnaButtonText));
                }, 3000);
            });
        });
    }

    //Express Checkout start
    const orderData = {
        mode: 'payment',
        amount: stripeTotalAmount * 100,
        currency: stripeCurrencyCode.toLowerCase(),
    };

    // Check if currency is supported by Klarna
    const supportedKlarnaCurrencies = ['eur', 'dkk', 'gbp', 'nok', 'sek', 'usd', 'czk', 'ron', 'aud', 'nzd', 'cad', 'pln', 'chf'];
    const isKlarnaSupported = supportedKlarnaCurrencies.includes(stripeCurrencyCode.toLowerCase());
    

    // Define payment methods for express checkout
    const paymentMethodsConfig = {
            card: 'always',
            applePay: 'always',
            googlePay: 'always',
        link: 'auto',
        klarna: 'auto'  // Changed from 'always' to 'auto'
    };
    
    // Add Klarna if supported
    if (isKlarnaSupported) {
        console.log('Klarna is supported for this currency');
    } else {
        console.log('Klarna not supported for this currency, but we are showing it anyway for testing');
    }

    const expressCheckoutOptions = {
        paymentMethods: paymentMethodsConfig,
        paymentMethodOrder: ['card', 'googlePay', 'applePay', 'link', 'klarna'],
        buttonType: {
            applePay: 'buy',
            googlePay: 'order',
        },
        layout: {
            maxColumns: 0,
            maxRows: 0,
            overflow: 'never'
        }
    };
    
    
    const elements = stripe.elements({
        locale: 'en',
        mode: 'payment',
        amount: stripeTotalAmount * 100,
        currency: stripeCurrencyCode.toLowerCase(),
    });
    const expressCheckoutElement = elements.create(
        'expressCheckout',
        expressCheckoutOptions
    )
    expressCheckoutElement.mount("#express-checkout-element");
    
    // Keep express checkout hidden
    $('#express-checkout-element').addClass('hidden');

    expressCheckoutElement.on('click', (event) => {
        const options = {
            emailRequired: true
        };
        event.resolve(options);
    });


    expressCheckoutElement.on('confirm', (event) => {
        const requestData = {
            order: orderData,
            paymentMethod: 'stripe',
            stripeToken: 'express'
        };
        
     
        fetch(stripeExpressPayLink,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(requestData)
            })

            .then(function (response) {
                responseClone = response.clone();
                return response.json();
            })
            .then(function (paymentIntent) {

                const clientSecret = paymentIntent.clientSecret;
                const { error } = stripe.confirmPayment({
                    elements,
                    clientSecret,
                    confirmParams: {
                        return_url: stripeSuccessLink,
                    },
                });
                if (error) {
                    console.error('Error confirming payment:', error);
                }
            }, function (rejectionReason) {

                responseClone.text()
                    .then(function (bodyText) {

                    });
            });
    });

    // Show Klarna button when Stripe is selected
 
    
    // Initially show Klarna button if Stripe is selected
    // if ($('input[name=paymentMethod]:checked', '#paymentForm').val() === 'stripe') {
    //     $('#stripe-checkout-btn').removeClass('hidden');
    //     // Ensure express checkout stays hidden
    //     $('#express-checkout-element').addClass('hidden');
    // }

}
