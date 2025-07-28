<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "appointments";

    protected $fillable = [
        'id',
        'order_id',
        'date',
        'time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'         => 'integer',
        'order_id'   => 'integer',
        'date'       => 'date',
        'time'       => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }
}
