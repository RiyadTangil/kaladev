<?php

namespace App\Http\Resources;


use App\Enums\Ask;
use App\Enums\IsAdvance;
use App\Enums\OrderType;
use App\Enums\PaymentStatus;
use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class WebhookOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'order_serial_no'                => $this->order_serial_no,
            'branch_id'                      => $this->branch_id,
            'rider_tips'                     => AppLibrary::currencyAmountFormat($this->rider_tip),
            "discount"                       => AppLibrary::currencyAmountFormat($this->discount),
            "point_discount"                 => AppLibrary::currencyAmountFormat($this->point_discount_amount),
            "total_currency_price"           => AppLibrary::currencyAmountFormat($this->total),
            'payment_method'                 => strtoupper($this?->transaction?->payment_method),
            'payment_status'                 => $this->payment_status == PaymentStatus::PAID ? 'Paid' : 'Unpaid',
            'order_type'                     => $this->getOrderType($this->order_type),
            'status'                         => $this->status,
            'order_datetime'                 => AppLibrary::datetime($this->order_datetime),
            'is_advance_order'               => $this->is_advance_order == IsAdvance::YES ? 'Yes' : 'No',
            'delivery_date'                  => $this->is_advance_order == Ask::YES ? AppLibrary::increaseDate($this->order_datetime, 1) : AppLibrary::date($this->order_datetime),
            'delivery_time'                  => $this->delivery_time,
            'status_name'                    => trans('orderStatus.' . $this->status),
            'customer'                       => new WebhookOrderUserResource($this->user),
            'address'                        => new WebhookOrderAddressResource($this->address),
            'items'                          => WebhookItemResource::collection($this->orderItems)
        ];
    }

    public function getOrderType($type): string
    {
        if($type == OrderType::DELIVERY){
            return 'Delivery';
        }

        if($type === OrderType::TAKEAWAY){
            return 'Takeway';
        }
        return '';
    }
}
