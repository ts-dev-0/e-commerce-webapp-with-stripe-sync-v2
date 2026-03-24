<?php

namespace App\Actions\Cart;

use App\Models\User;

class AddItemToCart
{
    public function handle(User $user, int $productId, int $quantity): void
    {
        $cart = $user->currentCart();

        $existing = $cart->products()
                ->where('product_id', $productId)
                ->first();
        if($existing) {
            $cart->products()->updateExistingPivot(
                $productId,
                [
                    'quantity' => $existing->pivot->quantity + $quantity,
                ]
            );

            return;
        }

        $cart->products()->attach($productId, [
            'quantity' => $quantity,
        ]);
    }
}
