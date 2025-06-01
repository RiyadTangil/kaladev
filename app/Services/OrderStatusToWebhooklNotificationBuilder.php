<?php

namespace App\Services;


use App\Enums\Ask;
use App\Enums\IsAdvance;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Http\Resources\WebhookItemResource;
use App\Http\Resources\WebhookOrderAddressResource;
use App\Http\Resources\WebhookOrderResource;
use App\Http\Resources\WebhookOrderUserResource;
use App\Libraries\AppLibrary;
use Illuminate\Support\Facades\Http;
use App\Models\Branch;
use App\Models\Order;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderStatusToWebhooklNotificationBuilder
{
    public array $info;

    public function __construct($info)
    {
        $this->info = $info;
    }

    public function send()
    {

        if (!isset($this->info['order_id'])) {
            Log::info('Order id is missing on webhook info sending on order status changed');
            return;
        }


        $order = Order::withoutGlobalScope(Branch::class)
            ->with('orderItems', 'user', 'address')
            ->where('id', operator: $this->info['order_id'])
            ->first();

        if (!$order) {
            Log::info('Order is missing on webhook info sending on order status changed');
            return;
        }

        $branch = Branch::where('id', $order->branch_id)->first();
        if (!$branch || !$branch->webhook_url) {
            Log::error("Branch or Webhook URL not found for Order ID {$order->id}");
            return;
        }

        $payload =
         [
            'order_serial_no'                => $order->order_serial_no,
            'branch_id'                      => $order->branch_id,
            'rider_tips'                     => AppLibrary::currencyAmountFormat($order->rider_tip),
            "discount"                       => AppLibrary::currencyAmountFormat($order->discount),
            "point_discount"                 => AppLibrary::currencyAmountFormat($order->point_discount_amount),
            "total_currency_price"           => AppLibrary::currencyAmountFormat($order->total),
            'payment_method'                 => strtoupper($order?->transaction?->payment_method),
            'payment_status'                 => $order->payment_status == PaymentStatus::PAID ? 'Paid' : 'Unpaid',
            'order_type'                     => $this->getOrderType($order->order_type),
            'order_datetime'                 => AppLibrary::datetime($order->order_datetime),
            'status'                         => $order->status,
            'is_advance_order'               => $order->is_advance_order == IsAdvance::YES ? 'Yes' : 'No',
            'delivery_date'                  => $order->is_advance_order == Ask::YES ? AppLibrary::increaseDate($order->order_datetime, 1) : AppLibrary::date($order->order_datetime),
            'delivery_time'                  => $order->delivery_time,
            'status_name'                    => trans('orderStatus.' . $order->status),
            'customer'                       => new WebhookOrderUserResource($order->user),
            'address'                        => new WebhookOrderAddressResource($order->address),
            'items'                          => WebhookItemResource::collection($order->orderItems)
        ];


        try {

            $response = Http::post( $branch->webhook_url, $payload);
            // Log the response body
            Log::info('Webhook Response: ' . $response->body());


        } catch (Exception $execption) {
            Log::error(" Webhook error " . $execption->getMessage());
        }
    }

    public function getOrderType($type): string
    {
        if ($type == OrderType::DELIVERY) {
            return 'Delivery';
        }

        if ($type === OrderType::TAKEAWAY) {
            return 'Takeway';
        }
        return '';
    }
}




