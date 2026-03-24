<?php

namespace App\Actions\Cart;

use App\Models\User;

class RemoveCartItem
{
    public function handle(User $user, int $productId): void
    {
        $cart = $user->currentCart();

        $cart->products()->detach($productId);
    }
}
