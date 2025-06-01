<?php

namespace App\Http\Resources;

use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliverySetupResource extends JsonResource
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
            'id'                            => $this->id,
            'branch_id'                     => $this->branch_id,
            'kilometer_range'               => $this->kilometer_range,
            'minimum_order_amount'          => $this->minimum_order_amount,
            'delivery_charge'               => $this->delivery_charge,
            "flat_minimum_order_amount"     => AppLibrary::flatAmountFormat($this->minimum_order_amount),
            "convert_minimum_order_amount"  => AppLibrary::convertAmountFormat($this->minimum_order_amount),
            "currency_minimum_order_amount" => AppLibrary::currencyAmountFormat($this->minimum_order_amount),
            "flat_delivery_charge"          => AppLibrary::flatAmountFormat($this->delivery_charge),
            "convert_delivery_charge"       => AppLibrary::convertAmountFormat($this->delivery_charge),
            "currency_delivery_charge"      => AppLibrary::currencyAmountFormat($this->delivery_charge),
        ];
    }
}