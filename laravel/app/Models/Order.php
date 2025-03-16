<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'quote_id',
        'customer_id',
        'vehicle_id',
        'finance_type',
        'contract_length',
        'annual_mileage',
        'payment_profile',
        'deposit',
        'monthly_payment',
        'maintenance',
        'document_fee',
        'status',
        'token',
        'expires_at',
        'sent'
    ];

    protected $casts = [
        'maintenance' => 'boolean',
        'sent' => 'boolean',
        'expires_at' => 'datetime'
    ];

    // Define status constants for better code clarity
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CONFIRMED = 'confirmed';

    // Get all available statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ACTIVE,
            self::STATUS_EXPIRED,
            self::STATUS_CONFIRMED
        ];
    }

    // Check if status transition is valid
    public function canTransitionTo($newStatus)
    {
        // Define allowed transitions based on current status
        $allowedTransitions = [
            self::STATUS_PENDING => [self::STATUS_ACTIVE, self::STATUS_EXPIRED],
            self::STATUS_ACTIVE => [self::STATUS_PENDING, self::STATUS_EXPIRED, self::STATUS_CONFIRMED],
            self::STATUS_EXPIRED => [self::STATUS_PENDING, self::STATUS_ACTIVE],
            self::STATUS_CONFIRMED => [self::STATUS_PENDING, self::STATUS_EXPIRED],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->token = $order->token ?? Str::random(20);
            $order->expires_at = $order->expires_at ?? now()->addDays(28);
            $order->status = $order->status ?? self::STATUS_ACTIVE;
        });
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tracking()
    {
        return $this->hasMany(OrderTracking::class);
    }
}
