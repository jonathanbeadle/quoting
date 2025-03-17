<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'phone'
    ];

    public function quotes()
    {
        return $this->hasMany(\App\Models\Quote::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function deals()
    {
        return $this->hasMany(\App\Models\Deal::class);
    }
}
