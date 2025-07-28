<?php

namespace App\Models;

use App\Models\FootageSize;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "properties";

    protected $fillable = [
        'id',
        'order_id',
        'address',
        'city',
        'state',
        'zip_code',
        'property_type',
        'footage_size_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'              => 'integer',
        'order_id'        => 'integer',
        'address'         => 'string',
        'city'            => 'string',
        'state'           => 'string',
        'zip_code'        => 'string',
        'property_type'   => 'string',
        'footage_size_id' => 'integer',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function footageSize(): BelongsTo {
        return $this->belongsTo(FootageSize::class, 'footage_size_id');
    }

    /**
     * Set the address attribute with proper formatting
     */
    public function setAddressAttribute(string $value): void {
        $this->attributes['address'] = trim($value);
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
     * Get full address attribute
     */
    public function getFullAddressAttribute(): string {
        return $this->address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
    }

    /**
     * Scope for orders by footage size
     */
    public function scopeByFootageSize($query, int $footageSizeId) {
        return $query->where('footage_size_id', $footageSizeId);
    }
}
