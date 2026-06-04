<?php

namespace App\Actions\CartItem;

use App\Models\Product;
use App\Models\User;

class UpdateCartItemQuantity
{
    public function handle(User $user, int $productId, int $updateQuantity): void
    {
        /** @var \App\Models\Product $product */
        $product = Product::find($productId, ['*']);
        if (! $product->hasEnoughStock($updateQuantity)) {
            throw new \App\Exceptions\InsufficientStockException('There is not enough stock available for this product.');
        }

        $cart = $user->currentCart();

        /** @var \App\Models\CartItem | null $cartItem */
        $cartItem = $cart->items()
            ->where('product_id', $productId)
            ->first();

        if ($cartItem === null) {
            throw new \App\Exceptions\CartItemNotFoundException('Cart item not found.');
        }

        $cartItem->update(['quantity' => $updateQuantity]);
    }
}
