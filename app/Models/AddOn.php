<?php

namespace App\Models;

use App\Models\FootageSize;
use App\Models\OrderItem;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddOn extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'add_ons';

    protected $fillable = [
        'id',
        'footage_size_id',
        'service_item_id',
        'locations',
        'quantity',
        'price',
        'is_increment',
        'maximum_number',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'              => 'integer',
        'footage_size_id' => 'integer',
        'service_item_id' => 'integer',
        'locations'       => 'integer',
        'quantity'        => 'integer',
        'price'           => 'decimal:2',
        'is_increment'    => 'boolean',
        'maximum_number'  => 'integer',
        'status'          => 'string',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    public function footageSize(): BelongsTo {
        return $this->belongsTo(FootageSize::class, 'footage_size_id');
    }

    public function serviceItem(): BelongsTo {
        return $this->belongsTo(ServiceItem::class, 'service_item_id');
    }

    // Helper method to check if service is Community Images
    public function isCommunityImages(): bool {
        return $this->serviceItem && strtolower($this->serviceItem->service_name) === 'community image';
    }

    // Helper method to get display text
    public function getDisplayText(): string {
        if ($this->isCommunityImages()) {
            return "{$this->quantity} Community Images - {$this->locations} location(s)";
        }
        return "Quantity: {$this->quantity}";
    }

    /**
     * Any order items that point at this AddOn.
     */
    public function orderItems(): MorphMany {
        return $this->morphMany(OrderItem::class, 'itemable');
    }
}
