<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Deal;

class DealStatus extends Component
{
    public $deal;
    public $status;
    public $badgeClass;

    public function __construct($deal)
    {
        $this->deal = $deal;
        $this->status = $deal->status;
        $this->badgeClass = $this->getBadgeClass();
    }

    protected function getBadgeClass()
    {
        return match ($this->status) {
            Deal::STATUS_INITIAL => 'bg-secondary',
            Deal::STATUS_QUOTE_SENT => 'bg-info',
            Deal::STATUS_QUOTE_ACCEPTED => 'bg-success',
            Deal::STATUS_FINANCE_PROCESS => 'bg-warning',
            Deal::STATUS_ORDER_SENT => 'bg-primary',
            Deal::STATUS_ORDER_ACCEPTED => 'bg-success',
            Deal::STATUS_ORDER_PROCESS => 'bg-warning',
            Deal::STATUS_CLOSED => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    public function render()
    {
        return view('components.deal-status');
    }
}