<?php

namespace App\Actions\OrderItem;

use App\Models\Cart;
use App\Models\Order;

class CreateOrderItem
{
    public function handle(Cart $cart, Order $order): void
    {
        foreach ($cart->products()->get() as $product) {
            $quantity = $product->pivot->quantity;
            $price = $product->price;
            $subtotal = $price * $quantity;

            $order->addItem(product: $product, quantity: $quantity, subtotal: $subtotal);

            $product->decrement('stock', $quantity, []);
        }
    }
}
