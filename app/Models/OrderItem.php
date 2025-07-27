<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "order_items";

    protected $fillable = [
        'id',
        'order_id',
        'itemable_id',
        'itemable_type',
        'quantity',
        'unit_price',
        'line_total',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'            => 'integer',
        'order_id'      => 'integer',
        'itemable_id'   => 'integer',
        'itemable_type' => 'string',
        'quantity'      => 'integer',
        'unit_price'    => 'decimal:2',
        'line_total'    => 'decimal:2',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    /**
     * The purchased item, either a Service or an AddOn.
     */
    public function itemable(): MorphTo {
        return $this->morphTo();
    }

    /**
     * Computed line total.
     */
    public function getLineTotalAttribute(): float {
        return $this->quantity * $this->unit_price;
    }
}
