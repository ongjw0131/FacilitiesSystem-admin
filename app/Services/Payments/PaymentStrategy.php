<?php

namespace App\Services\Payments;

use App\Models\TicketOrder;
use App\Models\Event;

interface PaymentStrategy
{
    public function process(TicketOrder $order, Event $event);
}
