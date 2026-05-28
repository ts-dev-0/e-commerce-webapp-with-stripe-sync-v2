<?php

namespace App\DTOs;

use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;

readonly class CheckoutData
{
    /**
     * @param Collection<int, \App\Models\CartItem> $cartItems
     * @param Collection<int, \App\Models\Address> $addresses
     */
    public function __construct(
        public Collection $cartItems,
        public Collection $addresses,
        public array $deliveryDate,
        public int $shippingFee,
        public int $subtotal,
        public int $total,
    ) {}
}
