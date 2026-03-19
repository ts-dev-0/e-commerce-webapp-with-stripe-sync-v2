<?php

namespace App\Actions\User\Checkout;

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
        $cartItems = CartItem::with('product')
            ->whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $deliveryDate = $this->deliveryDateService->generate();

        return new CheckoutData($cartItems, $subtotal, $deliveryDate);
    }
}
