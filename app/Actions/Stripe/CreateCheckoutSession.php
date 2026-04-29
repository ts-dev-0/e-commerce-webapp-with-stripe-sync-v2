<?php

namespace App\Actions\Stripe;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CreateCheckoutSession
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * @throws \DomainException
     */
    public function handle(User $user, int $addressId): string
    {
        $cartItems = $user->currentCart()->products()->get();

        if ($cartItems->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }

        $session = $this->createSession([
            'payment_method_types' => ['card'],
            'line_items' => $this->mapCartItemsToStripeFormat($cartItems),
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failed'),
            'metadata' => [
                'user_id' => $user->id,
                'address_id' => $addressId,
            ],
        ]);

        return $session->url;
    }

    protected function createSession(array $payload): Session
    {
        return Session::create($payload);
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
