<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'packages';

    protected $fillable = [
        'id',
        'title',
        'image',
        'name',
        'description',
        'is_popular',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'          => 'integer',
        'title'       => 'string',
        'image'       => 'string',
        'name'        => 'string',
        'description' => 'string',
        'is_popular'  => 'boolean',
        'status'      => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];
}
