<?php

namespace App\Http\Resources\Api\Pricing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddOnResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'           => $this->id,
            'service_item' => $this->serviceItem?->service_name,
            'price'        => number_format($this->price, 2, '.', ''),
            'quantity'     => $this->quantity,
            'locations'    => $this->isCommunityImages() ? $this->locations : null,
            'display_text' => $this->getDisplayText(),
        ];
    }
}
