<?php

namespace App\Models;

use App\Models\FootageSize;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "services";

    protected $fillable = [
        'id',
        'package_id',
        'footage_size_id',
        'quantity',
        'price',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'              => 'integer',
        'package_id'      => 'integer',
        'footage_size_id' => 'integer',
        'quantity'        => 'integer',
        'price'           => 'decimal:2',
        'status'          => 'string',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'deleted_at'      => 'datetime',
    ];

    public function package(): BelongsTo {
        return $this->belongsTo(Package::class);
    }

    public function footageSize(): BelongsTo {
        return $this->belongsTo(FootageSize::class, 'footage_size_id', 'id');
    }
}
