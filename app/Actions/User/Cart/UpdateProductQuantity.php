<?php

namespace App\Actions\User\Cart;

use App\Models\Product;
use App\Models\User;

class UpdateProductQuantity
{
    public function handle(User $user, Product $product, int $updateQuantity): void
    {
        $cart = $user->currentCart();

        $existing = $cart->products()
                ->where('product_id', $product->id)
                ->exists();
        if(! $existing) {
            throw new \InvalidArgumentException("Product does not exists in user's cart");
        }

        $cart->products()->updateExistingPivot(
            $product->id,
            [
                'quantity' => $updateQuantity,
            ]
        );
    }
}
