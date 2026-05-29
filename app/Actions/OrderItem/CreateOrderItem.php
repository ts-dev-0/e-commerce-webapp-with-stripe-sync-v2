<?php

namespace App\Actions\OrderItem;

use App\Models\Order;
use App\Models\User;

class CreateOrderItem
{
    public function handle(User $user, Order $order): void
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
}
