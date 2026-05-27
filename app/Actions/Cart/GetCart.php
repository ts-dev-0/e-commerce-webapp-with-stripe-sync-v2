<?php

namespace App\Actions\Cart;

use App\DTOs\CartData;
use App\Models\User;

class GetCart
{
    public function handle(User $user): CartData
    {
        $cartItems = $user->currentCart()->items()->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return new CartData($cartItems, $subtotal);
    }
}
