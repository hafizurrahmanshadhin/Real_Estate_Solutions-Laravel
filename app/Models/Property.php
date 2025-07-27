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
}
