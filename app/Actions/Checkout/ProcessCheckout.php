<?php

namespace App\Actions\Checkout;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;

class ProcessCheckout
{
    public function handle(User $user, string $sessionId)
    {
        $session = Session::retrieve($sessionId);

        $addressId = $session->metadata->address_id;
        $deliveryAddress = Address::findOrFail($addressId);
        $totalAmount = $session->amount_total;
        $orderNumber = 'ORD-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'total_amount' => $totalAmount,
            'full_name' => $deliveryAddress->full_name,
            'postal_code' => $deliveryAddress->postal_code,
            'prefecture' => $deliveryAddress->prefecture,
            'city' => $deliveryAddress->city,
            'address_line' => $deliveryAddress->address_line,
            'phone_number' => $deliveryAddress->phone_number,
            'ordered_at' => now(),
        ]);

        $cart = $user->currentCart();
        $cartItems = $cart->products()->get();

        foreach ($cartItems as $product) {
            $quantity = $product->pivot->quantity;

            $price = $product->price;

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $price * $quantity,
            ]);

            $product->decrement('stock', $quantity);
        }

        $cart->products()->detach();

        return $order;
    }
}
