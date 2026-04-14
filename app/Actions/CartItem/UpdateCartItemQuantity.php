<?php

namespace App\Actions\CartItem;

use App\Models\User;

class UpdateCartItemQuantity
{
    public function handle(User $user, int $productId, int $updateQuantity): void
    {
        $cart = $user->currentCart();

        $existing = $cart->products()
                ->where('product_id', $productId)
                ->exists();
        if(! $existing) {
            throw new \InvalidArgumentException("Product does not exists in user's cart");
        }

        $cart->products()->updateExistingPivot(
            $productId,
            [
                'quantity' => $updateQuantity,
            ]
        );
    }
}
