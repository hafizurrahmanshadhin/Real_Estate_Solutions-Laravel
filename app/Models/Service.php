<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'package_id',
        'footage_size_id',
        'price',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function package() {
        return $this->belongsTo(Package::class);
    }

    public function footageSize() {
        return $this->belongsTo(FootageSize::class);
    }

    public function serviceItems() {
        return $this->belongsToMany(ServiceItem::class, 'service_items_pivot')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query) {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query) {
        return $query->where('status', 'inactive');
    }
}
