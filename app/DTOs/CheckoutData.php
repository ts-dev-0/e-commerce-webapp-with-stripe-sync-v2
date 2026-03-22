<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

readonly class CheckoutData
{
    /**
     * @param Collection<int, \App\Models\CartItem> $cartItems
     * @param Collection<int, \App\Models\Address> $addresses
     */
    public function __construct(
        public Collection $cartItems,
        public int $subtotal,
        public array $deliveryDate,
        public Collection $addresses,
        public int $shippingFee,
        public int $total,
    ) {}
}
