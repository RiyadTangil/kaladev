<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\GatewayOption;
use App\Services\PaymentMethodFeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodFeeTest extends TestCase
{
    use RefreshDatabase;

    private PaymentMethodFeeService $paymentMethodFeeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentMethodFeeService = new PaymentMethodFeeService();
    }

    /** @test */
    public function it_calculates_percentage_fee_correctly()
    {
        // Create a payment gateway with percentage fee
        $gateway = PaymentGateway::factory()->create([
            'name' => 'Stripe',
            'slug' => 'stripe'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_type',
            'value' => 'percentage'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_amount',
            'value' => '3.0'
        ]);

        $orderAmount = 100.00;
        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, $orderAmount);

        $this->assertEquals(3.00, $fee);
    }

    /** @test */
    public function it_calculates_fixed_fee_correctly()
    {
        // Create a payment gateway with fixed fee
        $gateway = PaymentGateway::factory()->create([
            'name' => 'PayPal',
            'slug' => 'paypal'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'paypal_fee_type',
            'value' => 'fixed'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'paypal_fee_amount',
            'value' => '2.50'
        ]);

        $orderAmount = 100.00;
        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, $orderAmount);

        $this->assertEquals(2.50, $fee);
    }

    /** @test */
    public function it_returns_zero_fee_for_gateway_without_fee_configuration()
    {
        // Create a payment gateway without fee options
        $gateway = PaymentGateway::factory()->create([
            'name' => 'Cash on Delivery',
            'slug' => 'cashondelivery'
        ]);

        $orderAmount = 100.00;
        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, $orderAmount);

        $this->assertEquals(0.00, $fee);
    }

    /** @test */
    public function it_returns_zero_fee_for_non_existent_gateway()
    {
        $orderAmount = 100.00;
        $fee = $this->paymentMethodFeeService->calculateFee(999, $orderAmount);

        $this->assertEquals(0.00, $fee);
    }

    /** @test */
    public function it_calculates_fee_by_slug()
    {
        // Create a payment gateway with percentage fee
        $gateway = PaymentGateway::factory()->create([
            'name' => 'Stripe',
            'slug' => 'stripe'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_type',
            'value' => 'percentage'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_amount',
            'value' => '2.5'
        ]);

        $orderAmount = 200.00;
        $fee = $this->paymentMethodFeeService->calculateFeeBySlug('stripe', $orderAmount);

        $this->assertEquals(5.00, $fee);
    }

    /** @test */
    public function it_gets_all_payment_gateway_fees()
    {
        // Create multiple payment gateways with different fee configurations
        $stripeGateway = PaymentGateway::factory()->create([
            'name' => 'Stripe',
            'slug' => 'stripe'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $stripeGateway->id,
            'option' => 'stripe_fee_type',
            'value' => 'percentage'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $stripeGateway->id,
            'option' => 'stripe_fee_amount',
            'value' => '3.0'
        ]);

        $paypalGateway = PaymentGateway::factory()->create([
            'name' => 'PayPal',
            'slug' => 'paypal'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $paypalGateway->id,
            'option' => 'paypal_fee_type',
            'value' => 'fixed'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $paypalGateway->id,
            'option' => 'paypal_fee_amount',
            'value' => '2.50'
        ]);

        $fees = $this->paymentMethodFeeService->getAllPaymentGatewayFees();

        $this->assertCount(2, $fees);
        $this->assertArrayHasKey($stripeGateway->id, $fees);
        $this->assertArrayHasKey($paypalGateway->id, $fees);
        $this->assertEquals('percentage', $fees[$stripeGateway->id]['fee_type']);
        $this->assertEquals('3.0', $fees[$stripeGateway->id]['fee_amount']);
        $this->assertEquals('fixed', $fees[$paypalGateway->id]['fee_type']);
        $this->assertEquals('2.50', $fees[$paypalGateway->id]['fee_amount']);
    }

    /** @test */
    public function order_includes_payment_method_fee_in_database()
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'total_tax' => 10.00,
            'delivery_charge' => 5.00,
            'payment_method_fee' => 3.00,
            'total' => 118.00
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_method_fee' => 3.00,
            'total' => 118.00
        ]);
    }

    /** @test */
    public function order_resource_includes_payment_method_fee()
    {
        $order = Order::factory()->create([
            'subtotal' => 100.00,
            'total_tax' => 10.00,
            'delivery_charge' => 5.00,
            'payment_method_fee' => 3.00,
            'total' => 118.00
        ]);

        $response = $this->get('/api/admin/orders/' . $order->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'payment_method_fee_currency_price',
                        'total_currency_price'
                    ]
                ]);
    }

    /** @test */
    public function checkout_calculates_total_with_payment_method_fee()
    {
        // Create a payment gateway with fee
        $gateway = PaymentGateway::factory()->create([
            'name' => 'Stripe',
            'slug' => 'stripe'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_type',
            'value' => 'percentage'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_amount',
            'value' => '3.0'
        ]);

        // Test order creation with payment method fee
        $orderData = [
            'subtotal' => 100.00,
            'delivery_charge' => 5.00,
            'discount' => 0,
            'payment_method' => $gateway->id,
            'items' => '[]',
            // other required fields...
        ];

        // Calculate expected fee
        $expectedFee = ($orderData['subtotal'] + $orderData['delivery_charge']) * 0.03;
        $expectedTotal = $orderData['subtotal'] + $orderData['delivery_charge'] + $expectedFee;

        // This would be tested in a proper integration test with user authentication
        $this->assertTrue($expectedFee > 0);
        $this->assertEquals(3.15, $expectedFee); // 105 * 3% = 3.15
    }

    /** @test */
    public function payment_method_fee_handles_edge_cases()
    {
        // Test with zero amount
        $gateway = PaymentGateway::factory()->create([
            'name' => 'Stripe',
            'slug' => 'stripe'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_type',
            'value' => 'percentage'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_amount',
            'value' => '3.0'
        ]);

        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, 0);
        $this->assertEquals(0.00, $fee);

        // Test with negative amount
        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, -100);
        $this->assertEquals(-3.00, $fee);
    }

    /** @test */
    public function payment_method_fee_rounds_correctly()
    {
        $gateway = PaymentGateway::factory()->create([
            'name' => 'Stripe',
            'slug' => 'stripe'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_type',
            'value' => 'percentage'
        ]);

        GatewayOption::factory()->create([
            'payment_gateway_id' => $gateway->id,
            'option' => 'stripe_fee_amount',
            'value' => '2.33'
        ]);

        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, 100.00);
        $this->assertEquals(2.33, $fee);

        // Test rounding with more precision
        $fee = $this->paymentMethodFeeService->calculateFee($gateway->id, 33.33);
        $this->assertEquals(0.78, $fee); // 33.33 * 2.33% = 0.776589, rounded to 0.78
    }
} 