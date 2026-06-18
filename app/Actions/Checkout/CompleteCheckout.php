<?php

namespace App\Actions\Checkout;

use App\Actions\Order\CreateOrder;
use App\Actions\OrderItem\CreateOrderItem;
use App\Models\User;
use App\Services\StripeSessionService;

class CompleteCheckout
{
    public function __construct(
        private StripeSessionService $stripeSessionService,
        private CreateOrder $createOrder,
        private CreateOrderItem $createOrderItem
    ) {}

    public function handle(
        User $user,
        string $sessionId,
    ): void {
        $session = $this->stripeSessionService->retrieveSession($sessionId);

        $deliveryAddress = $user->addresses->findOrFail($session->metadata->address_id);

        $order = $this->createOrder->handle($user, $session->amount_total, $deliveryAddress);

        $cart = $user->currentCart();

        $this->createOrderItem->handle($cart, $order);

        $cart->clear();
    }
}
