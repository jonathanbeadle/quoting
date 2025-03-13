<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'token'
    ];

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
}
