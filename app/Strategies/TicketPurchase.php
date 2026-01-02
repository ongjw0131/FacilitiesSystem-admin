<?php

namespace App\Strategies\TicketPurchase;

use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketOrder;
use App\Models\User;

interface TicketPurchaseStrategy
{
    public function handle(
        Event $event,
        EventTicket $ticket,
        TicketOrder $order,
        User $user
    ): array;
}
