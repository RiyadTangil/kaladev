<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class WebhookItemExtrasResource extends JsonResource
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
            "name"           => $this->name ?? "",
        ];
    }
}