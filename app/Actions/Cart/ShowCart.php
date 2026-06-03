<?php

namespace App\Actions\Cart;

use App\DTOs\CartData;
use App\Models\User;

class ShowCart
{
    public function handle(User $user)
    {
        $cart = $user->currentCart();
        $items = $cart->items()->with('product')->get();
        $subtotal = $cart->subtotal();

        return new CartData(items: $items, subtotal: $subtotal);
    }
}
