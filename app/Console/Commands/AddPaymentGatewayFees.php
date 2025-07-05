<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentGateway;
use App\Models\GatewayOption;
use App\Enums\InputType;

class AddPaymentGatewayFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:add-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add payment method fee options to all payment gateways';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Adding payment method fee options to all payment gateways...');
        $this->newLine();

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
        $bar = $this->output->createProgressBar(count($gatewayOptions));
        $bar->start();

        foreach ($gatewayOptions as $slug => $info) {
            $this->newLine();
            $this->line("Processing {$info['name']} ({$slug})...");
            
            // Find the payment gateway
            $gateway = PaymentGateway::where('slug', $slug)->first();
            
            if (!$gateway) {
                $this->error("  ❌ Gateway '{$slug}' not found in database");
                $bar->advance();
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
                $this->info("  ✅ Added {$slug}_fee_type");
                $addedOptions++;
            } else {
                $this->warn("  ⚠️  {$slug}_fee_type already exists");
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
                $this->info("  ✅ Added {$slug}_fee_amount");
                $addedOptions++;
            } else {
                $this->warn("  ⚠️  {$slug}_fee_amount already exists");
            }
            
            $totalAdded += $addedOptions;
            $this->line("  📊 Added {$addedOptions} options for {$info['name']}");
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('🎉 Script completed!');
        $this->info("📈 Total payment method fee options added: {$totalAdded}");
        $this->info('💼 All payment gateways now have fee configuration options!');
        $this->newLine();

        $this->comment('Next steps:');
        $this->line('1. Go to Admin Panel → Payment Gateway Settings');
        $this->line('2. Configure fee type (Fixed/Percentage) and amount for each gateway');
        $this->line('3. Test checkout process with different payment methods');

        return 0;
    }
} 