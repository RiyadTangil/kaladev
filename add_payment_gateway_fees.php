<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PaymentGateway;
use App\Models\GatewayOption;
use App\Enums\InputType;

echo "Adding payment method fee options to all payment gateways...\n\n";

// List of all gateways that should have fee options
$gatewayOptions = [
    'razorpay' => ['name' => 'Razorpay'],
    'cashfree' => ['name' => 'Cashfree'],
    'bkash' => ['name' => 'Bkash'],
    'flutterwave' => ['name' => 'Flutterwave'],
    'iyzico' => ['name' => 'Iyzico'],
    'mercadopago' => ['name' => 'Mercadopago'],
    'midtrans' => ['name' => 'Midtrans'],
    'mollie' => ['name' => 'Mollie'],
    'payfast' => ['name' => 'Payfast'],
    'paystack' => ['name' => 'Paystack'],
    'paytm' => ['name' => 'Paytm'],
    'pesapal' => ['name' => 'Pesapal'],
    'phonepe' => ['name' => 'PhonePe'],
    'senangpay' => ['name' => 'Senangpay'],
    'skrill' => ['name' => 'Skrill'],
    'sslcommerz' => ['name' => 'SslCommerz'],
    'telr' => ['name' => 'Telr'],
    'klarna' => ['name' => 'Klarna'],
];

$totalAdded = 0;

foreach ($gatewayOptions as $slug => $info) {
    echo "Processing {$info['name']} ({$slug})...\n";
    
    // Find the payment gateway
    $gateway = PaymentGateway::where('slug', $slug)->first();
    
    if (!$gateway) {
        echo "  ❌ Gateway '{$slug}' not found in database\n";
        continue;
    }
    
    // Check if fee options already exist
    $existingOptions = GatewayOption::where('payment_gateway_id', $gateway->id)
        ->whereIn('option', [
            $slug . '_fee_type',
            $slug . '_fee_amount'
        ])
        ->pluck('option')
        ->toArray();
    
    $addedOptions = 0;
    
    // Add fee_type option if it doesn't exist
    if (!in_array($slug . '_fee_type', $existingOptions)) {
        GatewayOption::create([
            'payment_gateway_id' => $gateway->id,
            'option' => $slug . '_fee_type',
            'value' => 'fixed',
            'type' => InputType::SELECT,
            'activities' => json_encode([
                'percentage' => 'percentage',
                'fixed' => 'fixed'
            ])
        ]);
        echo "  ✅ Added {$slug}_fee_type\n";
        $addedOptions++;
    } else {
        echo "  ⚠️  {$slug}_fee_type already exists\n";
    }
    
    // Add fee_amount option if it doesn't exist
    if (!in_array($slug . '_fee_amount', $existingOptions)) {
        GatewayOption::create([
            'payment_gateway_id' => $gateway->id,
            'option' => $slug . '_fee_amount',
            'value' => '0.00',
            'type' => InputType::TEXT,
            'activities' => ''
        ]);
        echo "  ✅ Added {$slug}_fee_amount\n";
        $addedOptions++;
    } else {
        echo "  ⚠️  {$slug}_fee_amount already exists\n";
    }
    
    $totalAdded += $addedOptions;
    echo "  📊 Added {$addedOptions} options for {$info['name']}\n\n";
}

echo "🎉 Script completed!\n";
echo "📈 Total payment method fee options added: {$totalAdded}\n";
echo "💼 All payment gateways now have fee configuration options!\n\n";

echo "Next steps:\n";
echo "1. Go to Admin Panel → Payment Gateway Settings\n";
echo "2. Configure fee type (Fixed/Percentage) and amount for each gateway\n";
echo "3. Test checkout process with different payment methods\n";

?> 