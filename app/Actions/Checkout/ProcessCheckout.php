<?php

namespace App\Actions\Checkout;

use App\Models\Order;
use App\Models\User;

class ProcessCheckout
{
    public function handle(User $user): Order
    {
        $cart = $user->currentCart();
        $cartItems = $cart->products()->get();

        if($cartItems->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }

        $totalAmount = $cartItems->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $order = Order::create([
            'user_id'      => $cart->user_id,
            'total_amount' => $totalAmount,
        ]);

        foreach ($cartItems as $product) {
            $quantity = $product->pivot->quantity;
            $price    = $product->price;

            $order->items()->create([
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'quantity'     => $quantity,
                'price'        => $price,
                'subtotal'     => $price * $quantity,
            ]);
        }

        $cart->products()->detach();

        return $order;
    }
}
