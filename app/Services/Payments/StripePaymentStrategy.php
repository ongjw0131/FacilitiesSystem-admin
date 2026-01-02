<?php

namespace App\Services\Payments;

use App\Models\TicketOrder;
use App\Models\Event;
use App\Services\PaymentService;

class StripePaymentStrategy implements PaymentStrategy
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function process(TicketOrder $order, Event $event)
    {
        $session = $this->paymentService->createCheckoutSession($order, $event);

        return [
            'type' => 'paid',
            'redirect' => $session->url
        ];
    }
}