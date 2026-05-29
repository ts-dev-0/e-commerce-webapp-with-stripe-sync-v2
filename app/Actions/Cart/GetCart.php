<?php

namespace App\Actions\Cart;

use App\DTOs\CartData;
use App\Models\User;

class GetCart
{
    public function handle(User $user): CartData
    {
        $cart = $user->currentCart();
        $cartItems = $cart->items()->get();

        $subtotal = $cart->subtotal();

        return new CartData(items: $cartItems, subtotal: $subtotal);
    }
}
