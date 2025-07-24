<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'contact_us';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'message',
        'is_agree',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'           => 'integer',
        'first_name'   => 'string',
        'last_name'    => 'string',
        'email'        => 'string',
        'phone_number' => 'string',
        'message'      => 'string',
        'is_agree'     => 'boolean',
        'status'       => 'string',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'deleted_at'   => 'datetime',
    ];
}
