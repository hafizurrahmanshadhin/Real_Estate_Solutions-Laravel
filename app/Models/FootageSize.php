<?php

namespace App\Models;

use App\Models\AddOn;
use App\Models\OtherServiceOrder;
use App\Models\Property;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FootageSize extends Model {
    use HasFactory;

    protected $table = 'footage_sizes';

    protected $fillable = [
        'id',
        'size',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id'         => 'integer',
        'size'       => 'string',
        'status'     => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function services(): HasMany {
        return $this->hasMany(Service::class);
    }

    public function addOns(): HasMany {
        return $this->hasMany(AddOn::class);
    }

    public function otherServiceOrders(): HasMany {
        return $this->hasMany(OtherServiceOrder::class, 'footage_size_id');
    }

    public function properties(): HasMany {
        return $this->hasMany(Property::class, 'footage_size_id');
    }
}
