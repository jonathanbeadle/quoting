<?php

namespace App\Observers;

use App\Models\Quote;
use App\Models\Deal;

class QuoteObserver
{
    public function updated(Quote $quote)
    {
        if ($quote->deal_id) {
            $deal = $quote->deal;
            
            // When a quote is sent
            if ($quote->wasChanged('sent') && $quote->sent) {
                if ($deal->status === Deal::STATUS_INITIAL) {
                    $deal->update(['status' => Deal::STATUS_QUOTE_SENT]);
                }
            }

            // When a quote is confirmed/accepted
            if ($quote->wasChanged('status') && $quote->status === 'confirmed') {
                if ($deal->status === Deal::STATUS_QUOTE_SENT) {
                    $deal->update(['status' => Deal::STATUS_QUOTE_ACCEPTED]);
                }
            }
        }
    }
}