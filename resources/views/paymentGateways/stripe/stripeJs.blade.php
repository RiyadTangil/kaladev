@php $stripeKey = ""; @endphp
@if (!blank($paymentGateways))
    @foreach ($paymentGateways as $paymentGateway)
        @if ($paymentGateway->slug === 'stripe')
            @php
                $paymentGatewayOption = $paymentGateway->gatewayOptions->pluck('value', 'option');
                $stripeKey = $paymentGatewayOption['stripe_key'];
            @endphp
        @endif
    @endforeach
@endif



<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripeKey = '<?= $stripeKey ?>';
    const stripeTotalAmount = '<?= $order->total ?>';
    const stripeCurrencyCode = '<?= $currency->code ?>';
    const stripeExpressPayLink = '<?= route('payment.store', ['order' => $order]) ?>';
    const stripeSuccessLink = '<?= route('payment.success', ['order' => $order, 'paymentGateway' => 'stripe']) ?>';
    const stripeCancelLink = '<?= route('payment.cancel', ['order' => $order, 'paymentGateway' => 'stripe']) ?>';
    const klarnaButtonText = '<?= trans('all.label.pay_with_klarna') ?>';
    
    // Debug information
    
</script>
<script src="{{ asset('paymentGateways/stripe/stripe.js') }}"></script>
