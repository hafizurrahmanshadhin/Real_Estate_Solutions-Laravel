<?php

namespace App\Models;

use App\Models\AddOn;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceItem extends Model {
    use HasFactory;

    protected $table = 'service_items';

    protected $fillable = [
        'id',
        'service_name',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id'           => 'integer',
        'service_name' => 'string',
        'status'       => 'string',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // Many-to-many relationship with Service through pivot table
    public function services(): BelongsToMany {
        return $this->belongsToMany(Service::class, 'service_items_pivot', 'service_item_id', 'service_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function addOns(): HasMany {
        return $this->hasMany(AddOn::class, 'service_item_id', 'id');
    }
}
