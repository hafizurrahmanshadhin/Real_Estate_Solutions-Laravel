<?php

namespace App\Http\Resources\Api\Pricing;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceByFootageResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'    => $this->id,
            'price' => number_format($this->price, 2, '.', ''),
            'items' => $this->serviceItems->map(fn($item) => [
                'name'     => $item->service_name,
                'quantity' => $item->pivot->quantity,
            ]),
        ];
    }
}
