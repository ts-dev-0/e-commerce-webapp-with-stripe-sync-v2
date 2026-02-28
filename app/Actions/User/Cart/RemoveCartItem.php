<?php

namespace App\Actions\User\Cart;

use App\Models\Product;
use App\Models\User;

class RemoveCartItem
{
    public function handle(User $user, Product $product): void
    {
        $cart = $user->currentCart();

        $cart->products()->detach($product->id);
    }
}
