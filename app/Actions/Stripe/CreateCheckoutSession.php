<?php

namespace App\Actions\Stripe;

use App\Models\User;
use App\Models\Product;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Support\Collection;

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

        $session = Session::create([
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
