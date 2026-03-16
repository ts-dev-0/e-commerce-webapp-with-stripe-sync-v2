<?php

namespace App\Actions\User\Cart;

use App\Models\CartItem;
use App\Models\User;

class GetCart
{
    public function handle(User $user): array
    {
        $cartItems = CartItem::with('product')
            ->whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        return [
            'items' => $cartItems->map(function ($item) {
                            return [
                                'product' => $item->product,
                                'quantity' => $item->quantity,
                            ];
                        })->toArray(),
        ];
    }
}
