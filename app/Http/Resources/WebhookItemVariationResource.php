<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class WebhookItemVariationResource extends JsonResource
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
            "variation_name"                   => $this->variation_name ?? "",
            "variation_value"                  => $this->name ?? "",
        ];
    }
}