<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

readonly class CartData
{
    /**
     * @param Collection<int, \App\Models\CartItem> $items
     */
    public function __construct(
        public Collection $items,
        public int $subtotal
    ) {}
}