<?php

namespace App\Models;

use App\Models\OtherServiceOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtherService extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'other_services';

    protected $fillable = [
        'id',
        'service_description',
        'title',
        'image',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'                  => 'integer',
        'service_description' => 'string',
        'title'               => 'string',
        'image'               => 'string',
        'description'         => 'string',
        'status'              => 'string',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'deleted_at'          => 'datetime',
    ];

    public function otherServiceOrders(): HasMany {
        return $this->hasMany(OtherServiceOrder::class, 'other_services_id');
    }
}
