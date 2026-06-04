<?php

namespace App\Actions\CartItem;

use App\Models\Product;
use App\Models\User;

class AddItemToCart
{
    public function handle(User $user, int $productId, int $quantity): void
    {
        /** @var \App\Models\Product $product */
        $product = Product::find($productId, ['*']);
        if (! $product->hasEnoughStock($quantity)) {
            throw new \App\Exceptions\InsufficientStockException('There is not enough stock available for this product.');
        }

        $cart = $user->currentCart();

        $cartItem = $cart->items()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        Product::whereKey($productId)
            ->decrement('stock', $quantity);
    }
}
