<?php

namespace App\Http\Resources\Api\ContactUs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'           => $this->id,
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'full_name'    => $this->first_name . ' ' . $this->last_name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'message'      => $this->message,
            'is_agree'     => $this->is_agree,
        ];
    }
}
