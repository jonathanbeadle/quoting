<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Deal;

class OrderObserver
{
    public function updated(Order $order)
    {
        if ($order->deal_id) {
            $deal = $order->deal;
            
            // When an order is sent
            if ($order->wasChanged('sent') && $order->sent) {
                if ($deal->status === Deal::STATUS_FINANCE_PROCESS) {
                    $deal->update(['status' => Deal::STATUS_ORDER_SENT]);
                }
            }

            // When an order is confirmed/accepted
            if ($order->wasChanged('status') && $order->status === 'confirmed') {
                if ($deal->status === Deal::STATUS_ORDER_SENT) {
                    $deal->update(['status' => Deal::STATUS_ORDER_ACCEPTED]);
                }
            }
        }
    }
}