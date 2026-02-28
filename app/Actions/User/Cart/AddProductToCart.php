<?php

namespace App\Actions\User\Cart;

use App\Models\Product;
use App\Models\User;

class AddProductToCart
{
    public function handle(User $user, Product $product, int $quantity): void
    {
        $cart = $user->currentCart();

        $existing = $cart->products()
                ->where('product_id', $product->id)
                ->first();
        if($existing) {
            $cart->products()->updateExistingPivot(
                $product->id,
                [
                    'quantity' => $existing->pivot->quantity + $quantity,
                ]
            );
        }

        $cart->products()->attach($product->id, [
            'quantity' => $quantity,
        ]);
    }
}
