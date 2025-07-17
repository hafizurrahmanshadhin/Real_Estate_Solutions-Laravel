<?php

namespace App\Models;

use App\Models\AddOn;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceItem extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'service_items';

    protected $fillable = [
        'id',
        'service_name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'           => 'integer',
        'service_name' => 'string',
        'status'       => 'string',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    public function services(): HasMany {
        return $this->hasMany(Service::class, 'service_item_id', 'id');
    }

    public function addOns(): HasMany {
        return $this->hasMany(AddOn::class, 'service_item_id', 'id');
    }
}
