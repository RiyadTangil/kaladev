<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentGateway;
use App\Models\GatewayOption;
use App\Enums\InputType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all payment gateways
        $paymentGateways = PaymentGateway::with('gatewayOptions')->get();
        
        foreach ($paymentGateways as $gateway) {
            $existingOptions = $gateway->gatewayOptions->pluck('option')->toArray();
            
            // Skip if gateway already has fee options
            if (in_array($gateway->slug . '_fee_type', $existingOptions)) {
                continue;
            }
            
            // Skip credit and klarna as they have special handling
            if (in_array($gateway->slug, ['credit', 'cashondelivery'])) {
                continue;
            }
            
            // Add fee_type option
            GatewayOption::create([
                'model_id' => $gateway->id,
                'model_type' => 'App\Models\PaymentGateway',
                'option' => $gateway->slug . '_fee_type',
                'value' => 'fixed',
                'type' => InputType::SELECT,
                'activities' => json_encode([
                    'percentage' => 'percentage',
                    'fixed' => 'fixed'
                ])
            ]);
            
            // Add fee_amount option
            GatewayOption::create([
                'model_id' => $gateway->id,
                'model_type' => 'App\Models\PaymentGateway',
                'option' => $gateway->slug . '_fee_amount',
                'value' => '0.00',
                'type' => InputType::TEXT,
                'activities' => ''
            ]);
        }
        
        // Handle Klarna separately (it has different structure)
        $klarnaGateway = PaymentGateway::where('slug', 'klarna')->first();
        if ($klarnaGateway) {
            $existingOptions = $klarnaGateway->gatewayOptions->pluck('option')->toArray();
            
            if (!in_array('klarna_fee_type', $existingOptions)) {
                GatewayOption::create([
                    'model_id' => $klarnaGateway->id,
                    'model_type' => 'App\Models\PaymentGateway',
                    'option' => 'klarna_fee_type',
                    'value' => 'fixed',
                    'type' => InputType::SELECT,
                    'activities' => json_encode([
                        'percentage' => 'percentage',
                        'fixed' => 'fixed'
                    ])
                ]);
            }
            
            if (!in_array('klarna_fee_amount', $existingOptions)) {
                GatewayOption::create([
                    'model_id' => $klarnaGateway->id,
                    'model_type' => 'App\Models\PaymentGateway',
                    'option' => 'klarna_fee_amount',
                    'value' => '0.00',
                    'type' => InputType::TEXT,
                    'activities' => ''
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all payment gateways
        $paymentGateways = PaymentGateway::all();
        
        foreach ($paymentGateways as $gateway) {
            // Remove fee options for all gateways except paypal and stripe (which had them originally)
            if (!in_array($gateway->slug, ['paypal', 'stripe'])) {
                GatewayOption::where('model_id', $gateway->id)
                    ->where('model_type', 'App\Models\PaymentGateway')
                    ->whereIn('option', [
                        $gateway->slug . '_fee_type',
                        $gateway->slug . '_fee_amount'
                    ])
                    ->delete();
            }
        }
    }
};
