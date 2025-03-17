<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'title',
        'status'
    ];

    // Define status constants
    const STATUS_INITIAL = 'Initial Enquiry';
    const STATUS_QUOTE_SENT = 'Quote Sent';
    const STATUS_QUOTE_ACCEPTED = 'Quote Accepted';
    const STATUS_FINANCE_PROCESS = 'Finance Process';
    const STATUS_ORDER_SENT = 'Order Sent';
    const STATUS_ORDER_ACCEPTED = 'Order Accepted';
    const STATUS_ORDER_PROCESS = 'Order Process';
    const STATUS_CLOSED = 'Closed';

    // Get all available statuses
    public static function getStatuses()
    {
        return [
            self::STATUS_INITIAL,
            self::STATUS_QUOTE_SENT,
            self::STATUS_QUOTE_ACCEPTED,
            self::STATUS_FINANCE_PROCESS,
            self::STATUS_ORDER_SENT,
            self::STATUS_ORDER_ACCEPTED,
            self::STATUS_ORDER_PROCESS,
            self::STATUS_CLOSED
        ];
    }

    // Check if status transition is valid
    public function canTransitionTo($newStatus)
    {
        $allowedTransitions = [
            self::STATUS_INITIAL => [self::STATUS_QUOTE_SENT, self::STATUS_CLOSED],
            self::STATUS_QUOTE_SENT => [self::STATUS_QUOTE_ACCEPTED, self::STATUS_INITIAL, self::STATUS_CLOSED],
            self::STATUS_QUOTE_ACCEPTED => [self::STATUS_FINANCE_PROCESS, self::STATUS_CLOSED],
            self::STATUS_FINANCE_PROCESS => [self::STATUS_ORDER_SENT, self::STATUS_CLOSED],
            self::STATUS_ORDER_SENT => [self::STATUS_ORDER_ACCEPTED, self::STATUS_FINANCE_PROCESS, self::STATUS_CLOSED],
            self::STATUS_ORDER_ACCEPTED => [self::STATUS_ORDER_PROCESS, self::STATUS_CLOSED],
            self::STATUS_ORDER_PROCESS => [self::STATUS_CLOSED],
            self::STATUS_CLOSED => [] // Closed is final, no transitions out
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deal) {
            $deal->status = $deal->status ?? self::STATUS_INITIAL;
        });
    }
}