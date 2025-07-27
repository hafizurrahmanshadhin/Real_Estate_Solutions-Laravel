<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderRequestResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'                 => $this->id,
            'full_name'          => $this->full_name,
            'phone_number'       => $this->phone_number,
            'email'              => $this->email,
            'footage_size_name'  => $this->footageSize?->size,
            'other_service_name' => $this->otherService?->title,
            'full_address'       => $this->full_address,
            'additional_info'    => $this->additional_info,
        ];
    }
}
