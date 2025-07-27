<?php

namespace App\Models;

use App\Models\FootageSize;
use App\Models\OtherService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherServiceOrder extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'other_service_orders';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'other_services_id',
        'additional_info',
        'address',
        'city',
        'state',
        'zip_code',
        'footage_size_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'                => 'integer',
        'first_name'        => 'string',
        'last_name'         => 'string',
        'phone_number'      => 'string',
        'email'             => 'string',
        'other_services_id' => 'integer',
        'additional_info'   => 'string',
        'address'           => 'string',
        'city'              => 'string',
        'state'             => 'string',
        'zip_code'          => 'string',
        'footage_size_id'   => 'integer',
        'status'            => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function otherService(): BelongsTo {
        return $this->belongsTo(OtherService::class, 'other_services_id');
    }

    public function footageSize(): BelongsTo {
        return $this->belongsTo(FootageSize::class, 'footage_size_id');
    }

    /**
     * Set the email attribute to lowercase
     */
    public function setEmailAttribute(string $value): void {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Set the first name attribute with proper formatting
     */
    public function setFirstNameAttribute(string $value): void {
        $this->attributes['first_name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Set the last name attribute with proper formatting
     */
    public function setLastNameAttribute(string $value): void {
        $this->attributes['last_name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Set the city attribute with proper formatting
     */
    public function setCityAttribute(string $value): void {
        $this->attributes['city'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Set the state attribute with proper formatting
     */
    public function setStateAttribute(string $value): void {
        $this->attributes['state'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Set the address attribute with proper formatting
     */
    public function setAddressAttribute(string $value): void {
        $this->attributes['address'] = trim($value);
    }

    /**
     * Set the additional info attribute
     */
    public function setAdditionalInfoAttribute(?string $value): void {
        $this->attributes['additional_info'] = $value ? trim($value) : null;
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get full address attribute
     */
    public function getFullAddressAttribute(): string {
        return $this->address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
    }

    /**
     * Scope for active orders
     */
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive orders
     */
    public function scopeInactive($query) {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for orders by email
     */
    public function scopeByEmail($query, string $email) {
        return $query->where('email', strtolower(trim($email)));
    }

    /**
     * Scope for orders by service
     */
    public function scopeByService($query, int $serviceId) {
        return $query->where('other_services_id', $serviceId);
    }

    /**
     * Scope for orders by footage size
     */
    public function scopeByFootageSize($query, int $footageSizeId) {
        return $query->where('footage_size_id', $footageSizeId);
    }
}
