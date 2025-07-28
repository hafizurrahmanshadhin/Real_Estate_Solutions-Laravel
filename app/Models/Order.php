<?php

namespace App\Models;

use App\Models\Appointment;
use App\Models\OrderItem;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
    use HasFactory, SoftDeletes;

    protected $table = "orders";

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'message',
        'is_agreed_privacy_policy',
        'stripe_session_id',
        'stripe_payment_intent',
        'payment_method',
        'transaction_id',
        'total_amount',
        'currency',
        'status',
        'order_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id'                       => 'integer',
        'first_name'               => 'string',
        'last_name'                => 'string',
        'email'                    => 'string',
        'phone_number'             => 'string',
        'message'                  => 'string',
        'is_agreed_privacy_policy' => 'boolean',
        'stripe_session_id'        => 'string',
        'stripe_payment_intent'    => 'string',
        'payment_method'           => 'string',
        'transaction_id'           => 'string',
        'total_amount'             => 'decimal:2',
        'currency'                 => 'string',
        'status'                   => 'string',
        'order_status'             => 'string',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
        'deleted_at'               => 'datetime',
    ];

    public function properties(): HasMany {
        return $this->hasMany(Property::class);
    }

    public function appointments(): HasMany {
        return $this->hasMany(Appointment::class);
    }

    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Set the email attribute to lowercase
     */
    public function setEmailAttribute(string $value): void {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Set the first name attribute with proper formatting
     */
    public function setFirstNameAttribute(string $value): void {
        $this->attributes['first_name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Set the last name attribute with proper formatting
     */
    public function setLastNameAttribute(string $value): void {
        $this->attributes['last_name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute(): string {
        return $this->first_name . ' ' . $this->last_name;
    }
}
