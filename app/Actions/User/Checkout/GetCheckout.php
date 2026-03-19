<?php

namespace App\Actions\User\Checkout;

use App\DTOs\CheckoutData;
use App\Models\CartItem;
use App\Models\User;

class GetCheckout
{
    public function handle(User $user): CheckoutData
    {
        $cartItems = CartItem::with('product')
            ->whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return new CheckoutData($cartItems, $subtotal);
    }
}
