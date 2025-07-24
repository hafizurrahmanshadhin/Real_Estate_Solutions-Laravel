<?php

namespace App\Http\Resources\Api;

use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource {
    protected $cmsData;

    /**
     * Create a new resource instance.
     *
     * @param mixed $resource
     * @param CMS|null $cmsData
     * @return void
     */
    public function __construct($resource, $cmsData = null) {
        parent::__construct($resource);
        $this->cmsData = $cmsData;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'      => $this->id,
            'type'    => $this->type,
            'image'   => $this->getContentImage(),
            'title'   => $this->title,
            'slug'    => $this->slug,
            'content' => $this->content,
        ];
    }

    /**
     * Get content image from CMS data
     *
     * @return string|null
     */
    private function getContentImage(): ?string {
        if ($this->cmsData && $this->cmsData->image) {
            return $this->cmsData->image;
        }

        // Return default image if no CMS data or image found
        return asset('backend/images/users/user-dummy-img.jpg');
    }
}
