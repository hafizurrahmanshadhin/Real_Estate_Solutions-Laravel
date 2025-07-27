<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtherServiceOrderResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'              => $this->id,
            'full_name'       => $this->full_name,
            'phone_number'    => $this->phone_number,
            'email'           => $this->email,
            'service'         => $this->otherService?->title,
            'additional_info' => $this->additional_info,
            'address'         => $this->full_address,
            'footage_size'    => $this->footageSize?->size,
        ];
    }
}
