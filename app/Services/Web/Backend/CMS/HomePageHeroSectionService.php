<?php

namespace App\Services\Web\Backend\CMS;

use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Http\UploadedFile;

class HomePageHeroSectionService {
    /**
     * Get or create hero section
     *
     * @return CMS
     */
    public function getOrCreateHeroSection(): CMS {
        return CMS::firstOrNew(['section' => 'home_page']);
    }

    /**
     * Update hero section with image handling
     *
     * @param array $data
     * @return void
     */
    public function updateHeroSection(array $data): void {
        $heroSection          = $this->getOrCreateHeroSection();
        $heroSection->items   = $data['titles'];
        $heroSection->title   = $data['titles'][0] ?? '';
        $heroSection->content = $data['content'];

        $this->handleImageUpload($heroSection, $data);

        $heroSection->save();
    }

    /**
     * Handle image upload, update, or removal
     *
     * @param CMS $heroSection
     * @param array $data
     * @return void
     */
    private function handleImageUpload(CMS $heroSection, array $data): void {
        // Check if image should be removed
        if (isset($data['remove_image']) && $data['remove_image']) {
            $this->removeExistingImage($heroSection);
            $heroSection->image = null;
            return;
        }

        // Check if new image is uploaded
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            // Remove existing image if it exists
            $this->removeExistingImage($heroSection);

            // Upload new image
            $uploadPath = $this->uploadImage($data['image']);
            if ($uploadPath) {
                $heroSection->image = $uploadPath;
            }
        }
    }

    /**
     * Remove existing image file
     *
     * @param CMS $heroSection
     * @return void
     */
    private function removeExistingImage(CMS $heroSection): void {
        if ($heroSection->image) {
            // Use the new method to get raw image path
            $imagePath = $heroSection->getRawImagePath();
            if ($imagePath) {
                Helper::fileDelete(public_path($imagePath));
            }
        }
    }

    /**
     * Upload image file
     *
     * @param UploadedFile $image
     * @return string|null
     */
    private function uploadImage(UploadedFile $image): ?string {
        try {
            // Generate a unique filename
            $filename = 'hero-section-' . time() . '-' . uniqid();

            // Upload the image using Helper
            return Helper::fileUpload($image, 'cms/hero-section', $filename);
        } catch (Exception $e) {
            return null;
        }
    }
}
