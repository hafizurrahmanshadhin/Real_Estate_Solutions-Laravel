<?php

namespace App\Models;

use App\Models\FootageSize;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddOn extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'add_ons';

    protected $fillable = [
        'id',
        'footage_size_id',
        'service_item_id',
        'quantity',
        'price',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'              => 'integer',
        'footage_size_id' => 'integer',
        'service_item_id' => 'integer',
        'quantity'        => 'integer',
        'price'           => 'decimal:2',
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
}
