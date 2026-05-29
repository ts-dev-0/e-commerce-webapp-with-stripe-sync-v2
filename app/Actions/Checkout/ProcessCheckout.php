<?php

namespace App\Actions\Checkout;

use App\Actions\Order\CreateOrder;
use App\Actions\OrderItem\CreateOrderItem;
use App\Models\Address;
use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ProcessCheckout
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function handle(User $user, string $sessionId): void
    {
        $session = Session::retrieve($sessionId);

        $deliveryAddress = Address::findOrFail($session->metadata->address_id);

        $order = (new CreateOrder)->handle($user, $session, $deliveryAddress);

        (new CreateOrderItem)->handle($user, $order);
        $cart = $user->currentCart();
        $cart->clear();
    }

    private function clearCart(User $user): void
    {
        $user->currentCart()
            ->products()
            ->detach();
    }
}
