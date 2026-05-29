<?php

namespace App\Actions\Checkout;

use App\DTOs\CheckoutData;
use App\Exceptions\EmptyCartItemException;
use App\Models\User;
use App\Services\Delivery\DeliveryDateService;

class GetCheckout
{
    public function __construct(
        private DeliveryDateService $deliveryDateService
    ) {}

    public function handle(User $user): CheckoutData
    {
        $cart = $user->currentCart();
        $cartItems = $cart->items()->with('product')->get();

        if ($cartItems->isEmpty()) {
            throw new EmptyCartItemException('Cart items is empty.');
        }

        $deliveryDate = $this->deliveryDateService->generate();

        $addresses = $user
            ->addresses()
            ->orderByDesc('is_default')
            ->get();

        $shippingFee = 0;
        $subtotal = $cart->subtotal();
        $total = $cart->total($shippingFee);

        return new CheckoutData(
            cartItems: $cartItems,
            addresses: $addresses,
            deliveryDate: $deliveryDate,
            shippingFee: $shippingFee,
            subtotal: $subtotal,
            total: $total,
        );
    }
}
