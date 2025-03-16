<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasFactory;

    protected $table = 'order_trackings';
    
    protected $fillable = [
        'order_id',
        'event_type',
        'ip_address',
        'user_agent',
        'user_id',
        'expires_at',
        'metadata'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'metadata' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}