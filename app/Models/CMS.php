<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CMS extends Model {
    use HasFactory, SoftDeletes, Notifiable, HasApiTokens;

    protected $table = 'c_m_s';

    protected $fillable = [
        'id',
        'section',
        'title',
        'sub_title',
        'description',
        'content',
        'image',
        'banner',
        'items',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'          => 'integer',
        'section'     => 'string',
        'title'       => 'string',
        'sub_title'   => 'string',
        'description' => 'string',
        'content'     => 'string',
        'image'       => 'string',
        'banner'      => 'string',
        'items'       => 'array',
        'status'      => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    public function setItemsAttribute($value): void {
        if (is_array($value)) {
            $this->attributes['items'] = json_encode($value);
        } else {
            $this->attributes['items'] = null;
        }
    }

    public function getItemsAttribute($value): mixed {
        return is_array($value) ? $value : json_decode($value, true);
    }

    public function setSectionAttribute($value): void {
        $this->attributes['section'] = strtolower($value);
    }

    public function getSectionAttribute($value): string {
        return match ($value) {
            'home_page' => 'Home Page',
            'contact_us_page' => 'Contact Us Page',
            'others_page'     => 'Others Page',
            default           => ucfirst($value),
        };
    }

    public function getImageAttribute($value): mixed {
        if ($value) {
            if (strpos($value, 'http://') === 0 || strpos($value, 'https://') === 0) {
                return $value;
            } else {
                return asset($value);
            }
        }
        return asset('backend/images/users/user-dummy-img.jpg');
    }

    public function getBannerAttribute($value): mixed {
        if ($value) {
            if (strpos($value, 'http://') === 0 || strpos($value, 'https://') === 0) {
                return $value;
            } else {
                return asset($value);
            }
        }
        return asset('backend/images/users/user-dummy-img.jpg');
    }

    /**
     * Get the raw image path without asset() wrapper
     * Using Laravel's built-in getRawOriginal method with proper signature
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getRawOriginal($key = null, $default = null): mixed {
        return parent::getRawOriginal($key, $default);
    }

    /**
     * Get the raw image path specifically for image attribute
     *
     * @return string|null
     */
    public function getRawImagePath(): ?string {
        return $this->getRawOriginal('image');
    }

    /**
     * Get the raw banner path specifically for banner attribute
     *
     * @return string|null
     */
    public function getRawBannerPath(): ?string {
        return $this->getRawOriginal('banner');
    }

    /**
     * Get the full image URL with proper handling
     *
     * @return string
     */
    public function getImageUrl(): string {
        $rawImage = $this->getRawImagePath();

        if ($rawImage) {
            if (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://')) {
                return $rawImage;
            } else {
                return asset($rawImage);
            }
        }

        return asset('backend/images/users/user-dummy-img.jpg');
    }

    /**
     * Get the full banner URL with proper handling
     *
     * @return string
     */
    public function getBannerUrl(): string {
        $rawBanner = $this->getRawBannerPath();

        if ($rawBanner) {
            if (str_starts_with($rawBanner, 'http://') || str_starts_with($rawBanner, 'https://')) {
                return $rawBanner;
            } else {
                return asset($rawBanner);
            }
        }

        return asset('backend/images/users/user-dummy-img.jpg');
    }
}
