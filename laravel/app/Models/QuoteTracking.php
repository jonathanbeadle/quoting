<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteTracking extends Model
{
    protected $table = 'quote_tracking';

    protected $fillable = [
        'quote_id',
        'event_type',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
