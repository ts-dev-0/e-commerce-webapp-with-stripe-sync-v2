<?php

namespace App\Actions\Checkout;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class ProcessCheckout
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function handle(User $user, string $sessionId)
    {
        $session = $this->retrieveSession($sessionId);

        $deliveryAddress = $this->resolveAddress($session);

        $order = $this->createOrder($user, $session, $deliveryAddress);

        $this->createOrderItems($user, $order);

        $this->clearCart($user);

        return $order;
    }

    protected function retrieveSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }

    private function resolveAddress(Session $session): Address
    {
        return Address::findOrFail($session->metadata->address_id);
    }

    private function createOrder(
        User $user,
        Session $session,
        Address $address
    ): Order {
        return Order::create([
            'user_id' => $user->id,
            'order_number' => $this->generateOrderNumber(),
            'total_amount' => $session->amount_total,
            'full_name' => $address->full_name,
            'postal_code' => $address->postal_code,
            'prefecture' => $address->prefecture,
            'city' => $address->city,
            'address_line' => $address->address_line,
            'phone_number' => $address->phone_number,
            'ordered_at' => now(),
        ]);
    }

    private function createOrderItems(User $user, Order $order): void
    {
        $cart = $user->currentCart();
        $products = $cart->products()->get();

        foreach ($products as $product) {
            $quantity = $product->pivot->quantity;
            $price = $product->price;

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $price * $quantity,
            ]);

            $product->decrement('stock', $quantity, []);
        }
    }

    private function clearCart(User $user): void
    {
        $user->currentCart()
            ->products()
            ->detach();
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(Str::random(8));
    }
}
