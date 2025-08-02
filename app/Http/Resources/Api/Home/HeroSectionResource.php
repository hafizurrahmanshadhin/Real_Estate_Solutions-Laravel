<?php

namespace App\Http\Resources\Api\Home;

use App\Http\Resources\Api\Home\HeroServiceResource;
use App\Models\OtherService;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroSectionResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        // Fetch hero services (ID > 1)
        $heroServices = OtherService::where('id', '>', 1)->where('status', 'active')->get();
        // Fetch logo from system settings
        $logo = SystemSetting::value('logo');

        return [
            'titles'   => collect($this->items ?? [])->map(function ($title) {
                return [
                    'title'   => $title,
                ];
            })->values(),
            'image'    => $this->image ? url($this->image) : null,
            'logo'     => $logo ? url($logo) : null,
            'content'  => $this->content,
            'services' => HeroServiceResource::collection($heroServices),
        ];
    }
}
