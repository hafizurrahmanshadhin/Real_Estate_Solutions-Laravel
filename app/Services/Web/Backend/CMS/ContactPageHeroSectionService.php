<?php

namespace App\Services\Web\Backend\CMS;

use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ContactPageHeroSectionService {
    /**
     * Get or create hero section
     *
     * @return CMS
     */
    public function getOrCreateHeroSection(): CMS {
        return CMS::firstOrNew(['section' => 'contact_us_page']);
    }

    /**
     * Update hero section with image and banner handling
     *
     * @param array $data
     * @return void
     */
    public function updateHeroSection(array $data): void {
        $heroSection = $this->getOrCreateHeroSection();

        // Update basic fields
        $heroSection->title   = $data['title'];
        $heroSection->content = $data['content'];

        // Handle banner operations
        $this->handleBannerUpload($heroSection, $data);

        // Handle image operations
        $this->handleImageUpload($heroSection, $data);

        $heroSection->save();
    }

    /**
     * Handle banner upload, update, or removal
     *
     * @param CMS $heroSection
     * @param array $data
     * @return void
     */
    private function handleBannerUpload(CMS $heroSection, array $data): void {
        // Check if banner should be removed
        if (isset($data['remove_banner']) && $data['remove_banner']) {
            $this->removeExistingBanner($heroSection);
            $heroSection->banner = null;
            return;
        }

        // Check if new banner is uploaded
        if (isset($data['banner']) && $data['banner'] instanceof UploadedFile) {
            // Remove existing banner if it exists
            $this->removeExistingBanner($heroSection);

            // Upload new banner
            $uploadPath = $this->uploadBanner($data['banner']);
            if ($uploadPath) {
                $heroSection->banner = $uploadPath;
            }
        }
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
     * Remove existing banner file
     *
     * @param CMS $heroSection
     * @return void
     */
    private function removeExistingBanner(CMS $heroSection): void {
        if ($heroSection->banner) {
            $bannerPath = $heroSection->getRawBannerPath();
            if ($bannerPath) {
                Helper::fileDelete(public_path($bannerPath));
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
            $imagePath = $heroSection->getRawImagePath();
            if ($imagePath) {
                Helper::fileDelete(public_path($imagePath));
            }
        }
    }

    /**
     * Upload banner file
     *
     * @param UploadedFile $banner
     * @return string|null
     */
    private function uploadBanner(UploadedFile $banner): ?string {
        try {
            // Generate a unique filename
            $filename = 'contact-banner-' . time() . '-' . uniqid();

            // Upload the banner using Helper
            return Helper::fileUpload($banner, 'cms/contact-section/banners', $filename);
        } catch (Exception $e) {
            Log::error('Contact Page Banner Upload Error: ' . $e->getMessage());
            return null;
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
            $filename = 'contact-image-' . time() . '-' . uniqid();

            // Upload the image using Helper
            return Helper::fileUpload($image, 'cms/contact-section/images', $filename);
        } catch (Exception $e) {
            Log::error('Contact Page Image Upload Error: ' . $e->getMessage());
            return null;
        }
    }
}
