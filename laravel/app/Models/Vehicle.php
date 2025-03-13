<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'make',
        'model',
        'specification',
        'transmission',
        'fuel_type',
        'registration_status',
        'registration_date',
        'additional_options',
        'dealer_fit_options',
        'colour'
    ];

    public function quotes()
    {
        return $this->hasMany(\App\Models\Quote::class);
    }
}
