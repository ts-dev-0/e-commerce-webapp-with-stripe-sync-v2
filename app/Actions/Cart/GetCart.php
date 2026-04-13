<?php

namespace App\Actions\Cart;

use App\DTOs\CartData;
use App\Models\CartItem;
use App\Models\User;


class GetCart
{
    public function handle(User $user): CartData
    {
        $cartItems = CartItem::with('product')
            ->whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return new CartData($cartItems, $subtotal);
    }
}
