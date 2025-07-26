<?php

namespace App\Http\Resources\Api\Pricing;

use App\Http\Resources\Api\Pricing\ServiceByFootageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FetchPackageResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'name'        => $this->name,
            'is_popular'  => (bool) $this->is_popular,
            'services'    => ServiceByFootageResource::collection($this->services),
        ];
    }
}
