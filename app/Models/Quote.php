<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'sent'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'maintenance' => 'boolean',
        'sent' => 'boolean'
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quote) {
            $quote->token = Str::random(20); // Generate a unique token for the quote
            $quote->expires_at = now()->addDays(28); // Set expiration 28 days from creation
        });
    }
}
