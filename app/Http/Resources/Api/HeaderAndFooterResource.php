<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeaderAndFooterResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        $systemSetting = $this->system_setting;
        $socialMedias  = $this->social_medias;

        return [
            'logo'           => $systemSetting->logo ? url($systemSetting->logo) : asset('backend/images/PNG FILE-01-02.png'),
            'favicon'        => $systemSetting->favicon ? url($systemSetting->favicon) : asset('backend/images/PNG FILE-01-02.png'),
            'description'    => $systemSetting->description,
            'copyright_text' => $systemSetting->copyright_text,

            // Social Media Links
            'social_media'   => $socialMedias,
        ];
    }
}
