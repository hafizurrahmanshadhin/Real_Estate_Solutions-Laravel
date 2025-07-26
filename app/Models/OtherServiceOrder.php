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

    public function otherService(): BelongsTo {
        return $this->belongsTo(OtherService::class, 'other_services_id');
    }

    public function footageSize(): BelongsTo {
        return $this->belongsTo(FootageSize::class, 'footage_size_id');
    }
}
