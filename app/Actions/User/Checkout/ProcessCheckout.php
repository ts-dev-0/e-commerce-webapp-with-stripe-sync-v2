<?php

namespace App\Actions\User\Checkout;

use App\Models\Order;
use App\Models\User;

class ProcessCheckout
{
    public function handle(User $user): Order
    {
        $cart = $user->currentCart();
        $products = $cart->products()->get();

        if($products->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }

        $totalAmount = $products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $order = Order::create([
            'user_id'      => $cart->user_id,
            'total_amount' => $totalAmount,
        ]);

        foreach ($products as $product) {
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
