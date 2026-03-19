<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

readonly class CheckoutData
{
    /**
     * @param Collection<int, \App\Models\CartItem> $cartItems
     */
    public function __construct(
        public Collection $cartItems,
        public int $subtotal,
        public array $deliveryDate,
    ) {}
}
