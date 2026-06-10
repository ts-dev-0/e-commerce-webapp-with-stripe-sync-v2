<?php

namespace App\Actions\Checkout;

use App\Actions\Order\CreateOrder;
use App\Actions\OrderItem\CreateOrderItem;
use App\Models\User;
use App\Services\StripeSessionService;

class ProcessCheckout
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
        /**
         * Core logic:
         * 1. sessionIdを使ってSessionを生成
         * 2. ユーザーが指定した配送先を検索し取得
         * 3. Orderを生成
         * 4. OrderItemを生成
         * 5. Cartを空にする
         */
        $session = $this->stripeSessionService->retrieveStripeSession($sessionId);

        $deliveryAddress = $user->addresses->findOrFail($session->metadata->address_id);

        $order = $this->createOrder->handle($user, $session->total_amount, $deliveryAddress);

        $cart = $user->currentCart();

        $this->createOrderItem->handle($cart, $order);

        $cart->clear();
    }
}
