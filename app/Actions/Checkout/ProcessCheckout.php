<?php

namespace App\Actions\Checkout;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

class ProcessCheckout
{
    public function handle(User $user, int $addressId): Order
    {
        $cart = $user->currentCart();
        $cartItems = $cart->products()->get();

        if($cartItems->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }

        $totalAmount = $cartItems->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $delivaryAddress = Address::find($addressId);

        $orderNumber = 'ORD-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id'      => $cart->user_id,
            'order_number' => $orderNumber,
            'total_amount' => $totalAmount,
            'full_name' => $delivaryAddress->full_name,
            'postal_code' => $delivaryAddress->postal_code,
            'prefecture' => $delivaryAddress->prefecture,
            'city' => $delivaryAddress->city,
            'address_line' => $delivaryAddress->address_line,
            'phone_number' => $delivaryAddress->phone_number,
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
