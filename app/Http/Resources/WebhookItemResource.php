<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class WebhookItemResource extends JsonResource
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
            "name"                             => $this->orderItem?->name,
            "quantity"                         => $this->quantity,
            'item_variations'                  => WebhookItemVariationResource::collection(json_decode($this->item_variations)),
            'item_extras'                      => WebhookItemExtrasResource::collection(json_decode($this->item_extras)),
            'instruction'                      => $this->instruction,
        ];
    }
}