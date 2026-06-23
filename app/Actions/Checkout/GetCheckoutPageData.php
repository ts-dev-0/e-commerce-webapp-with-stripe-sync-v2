<?php

namespace App\Actions\Checkout;

use App\DTOs\CheckoutData;
use App\Models\User;

class GetCheckoutPageData
{
    public function handle(User $user): ?CheckoutData
    {
        $cart = $user->currentCart();

        $cartItems = $cart->items()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return null;
        }

        $addresses = $user->addresses()->get();

        $shippingFee = 0;

        $subtotal = $cart->subtotal();

        $total = $cart->total($shippingFee);

        return new CheckoutData(
            cartItems: $cartItems,
            addresses: $addresses,
            shippingFee: $shippingFee,
            subtotal: $subtotal,
            total: $total,
        );
    }
}
