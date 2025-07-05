<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentMethodFeeService
{
    /**
     * Calculate payment method fee for a given payment gateway and amount
     *
     * @param int $paymentGatewayId
     * @param float $orderAmount
     * @return float
     */
    public function calculateFee(int $paymentGatewayId, float $orderAmount): float
    {
        try {
            $paymentGateway = PaymentGateway::with('gatewayOptions')->find($paymentGatewayId);
            
            if (!$paymentGateway) {
                return 0.0;
            }

            $gatewayOptions = $paymentGateway->gatewayOptions->pluck('value', 'option')->toArray();
            
            // Get fee configuration for this gateway
            $feeType = $gatewayOptions[$paymentGateway->slug . '_fee_type'] ?? null;
            $feeAmount = $gatewayOptions[$paymentGateway->slug . '_fee_amount'] ?? 0;

            if (!$feeType || !$feeAmount) {
                return 0.0;
            }

            return $this->calculateFeeByType($feeType, $feeAmount, $orderAmount);
            
        } catch (Exception $exception) {
            Log::error('Payment method fee calculation error: ' . $exception->getMessage());
            return 0.0;
        }
    }

    /**
     * Calculate payment method fee based on payment gateway slug and amount
     *
     * @param string $paymentGatewaySlug
     * @param float $orderAmount
     * @return float
     */
    public function calculateFeeBySlug(string $paymentGatewaySlug, float $orderAmount): float
    {
        try {
            $paymentGateway = PaymentGateway::with('gatewayOptions')->where('slug', $paymentGatewaySlug)->first();
            
            if (!$paymentGateway) {
                return 0.0;
            }

            $gatewayOptions = $paymentGateway->gatewayOptions->pluck('value', 'option')->toArray();
            
            // Get fee configuration for this gateway
            $feeType = $gatewayOptions[$paymentGateway->slug . '_fee_type'] ?? null;
            $feeAmount = $gatewayOptions[$paymentGateway->slug . '_fee_amount'] ?? 0;

            if (!$feeType || !$feeAmount) {
                return 0.0;
            }

            return $this->calculateFeeByType($feeType, $feeAmount, $orderAmount);
            
        } catch (Exception $exception) {
            Log::error('Payment method fee calculation error: ' . $exception->getMessage());
            return 0.0;
        }
    }

    /**
     * Calculate fee based on type (percentage or fixed)
     *
     * @param string $feeType
     * @param float $feeAmount
     * @param float $orderAmount
     * @return float
     */
    private function calculateFeeByType(string $feeType, float $feeAmount, float $orderAmount): float
    {
        if ($feeType === 'percentage') {
            return round(($orderAmount * $feeAmount) / 100, 2);
        } elseif ($feeType === 'fixed') {
            return (float) $feeAmount;
        }
        
        return 0.0;
    }

    /**
     * Get all payment gateway fee configurations
     *
     * @return array
     */
    public function getAllPaymentGatewayFees(): array
    {
        try {
            $paymentGateways = PaymentGateway::with('gatewayOptions')->get();
            $fees = [];

            foreach ($paymentGateways as $gateway) {
                $gatewayOptions = $gateway->gatewayOptions->pluck('value', 'option')->toArray();
                
                $feeType = $gatewayOptions[$gateway->slug . '_fee_type'] ?? null;
                $feeAmount = $gatewayOptions[$gateway->slug . '_fee_amount'] ?? 0;

                if ($feeType && $feeAmount) {
                    $fees[$gateway->id] = [
                        'slug' => $gateway->slug,
                        'name' => $gateway->name,
                        'fee_type' => $feeType,
                        'fee_amount' => $feeAmount
                    ];
                }
            }

            return $fees;
            
        } catch (Exception $exception) {
            Log::error('Get payment gateway fees error: ' . $exception->getMessage());
            return [];
        }
    }
} 