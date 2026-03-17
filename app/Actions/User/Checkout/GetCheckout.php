<?php

namespace App\Actions\User\Checkout;

use App\Models\CartItem;
use App\Models\User;

class GetCheckout
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
            'subtotal' => $cartItems->sum(function ($item) {
                            return $item->product->price * $item->quantity;
            }),
        ];
    }
}
