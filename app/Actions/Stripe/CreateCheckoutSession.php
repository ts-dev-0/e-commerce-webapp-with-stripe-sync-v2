<?php

namespace App\Actions\Stripe;

use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CreateCheckoutSession
{
    public function handle(User $user, int $addressId): string
    {
        $cart = $user->currentCart();
        $cartItems = $cart->products()->get();

        if ($cartItems->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($cartItems as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => $product->pivot->quantity,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failed'),
        ]);

        return $session->url;
    }
}
