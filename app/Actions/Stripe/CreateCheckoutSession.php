<?php

namespace App\Actions\Stripe;

use App\Models\User;
use App\Services\StripeSessionService;

class CreateCheckoutSession
{
    public function __construct(
        private StripeSessionService $stripeSessionService
    ) {}

    public function handle(User $user, int $selectedAddressId): string
    {
        $cartItems = $user->currentCart()->items()->get();
        if ($cartItems->isEmpty()) {
            throw new \App\Exceptions\EmptyCartException('cart is empty');
        }

        $session = $this->stripeSessionService
            ->createCheckoutSession(
                $cartItems,
                $user,
                $selectedAddressId
            );

        return $session->url;
    }
}
