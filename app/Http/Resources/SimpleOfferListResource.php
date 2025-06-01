<?php

namespace App\Http\Resources;


use App\Libraries\AppLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleOfferListResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => $this->name,
            "slug"           => $this->slug,
            'amount'         => $this->amount === null ? 0 : $this->amount,
            "flat_amount"    => AppLibrary::flatAmountFormat($this->amount),
            "convert_amount" => AppLibrary::convertAmountFormat($this->amount),
            'day'            => $this->day,
            'day_name'       => trans('day.' . $this->day),
            'start_time'     => $this->start_time,
            'end_time'       => $this->end_time,
            'convert_time'   => AppLibrary::time($this->start_time) . ' - ' . AppLibrary::time($this->end_time),
            'status'         => $this->status,
            'image'          => $this->cover,
        ];
    }
}