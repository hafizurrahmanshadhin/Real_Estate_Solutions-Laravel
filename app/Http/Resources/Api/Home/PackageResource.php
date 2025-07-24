<?php

namespace App\Http\Resources\Api\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'image'       => url($this->image),
            'name'        => $this->name,
            'description' => $this->description,
            'is_popular'  => (bool) $this->is_popular,
            'starting_at' => $this->services_min_price !== null ? number_format($this->services_min_price, 2, '.', '') : null,
        ];
    }
}
