<?php

namespace App\Services\Payments;

use App\Models\Event;
use App\Models\TicketOrder;

class PaymentContext
{
    protected PaymentStrategy $strategy;

    public function __construct(PaymentStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    public function execute(TicketOrder $order, Event $event)
    {
        return $this->strategy->process($order, $event);
    }
}
