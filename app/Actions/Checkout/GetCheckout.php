<?php

namespace App\Actions\Checkout;

use App\DTOs\CheckoutData;
use App\Models\CartItem;
use App\Models\User;
use App\Services\Delivery\DeliveryDateService;

class GetCheckout
{
    public function __construct(
        private DeliveryDateService $deliveryDateService
    ) {}

    public function handle(User $user): CheckoutData
    {
        $cartItems = $user->currentCart()->items()->get();

        $deliveryDate = $this->deliveryDateService->generate();

        $addresses = $user
            ->addresses()
            ->orderByDesc('is_default')
            ->get();

        /** @var \App\Models\Address|null $defaultAddress */
        $defaultAddress = $addresses->firstWhere('is_default', true);
        
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Address> $anotherAddresses */
        $anotherAddresses = $addresses
            ->where('is_default', false)
            ->values();

        $shippingFee = 0;
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $total = $subtotal + $shippingFee;

        return new CheckoutData(
            $cartItems,
            $addresses,
            $defaultAddress,
            $anotherAddresses,
            $deliveryDate,
            $shippingFee,
            $subtotal,
            $total,
        );
    }
}
