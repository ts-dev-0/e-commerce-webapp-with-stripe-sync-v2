<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Stripe\Checkout\Session;

class StripeSessionService
{
    public function createCheckoutSession(
        Collection $cartItems,
        User $user,
        int $addressId
    ): Session {
        $payload = [
            'payment_method_types' => ['card'],
            'line_items' => $this->mapCartItemsToStripeFormat($cartItems),
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failed'),
            'metadata' => [
                'user_id' => $user->id,
                'address_id' => $addressId,
            ],
        ];

        return Session::create($payload);
    }

    public function retrieveStripeSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }

    private function mapCartItemsToStripeFormat(Collection $items): array
    {
        return $items->map(fn(Product $product) => [
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $product->name,
                ],
                'unit_amount' => $product->price,
            ],
            'quantity' => $product->pivot->quantity,
        ])->toArray();
    }
}
