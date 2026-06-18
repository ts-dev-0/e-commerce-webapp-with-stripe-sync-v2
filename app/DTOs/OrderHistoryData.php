<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

readonly class OrderHistoryData
{
    /**
     * @param Collection<int, \App\Models\Order> $orders
     */
    public function __construct(
        public Collection $orders,
        public array $availableYears,
    ) {
        //
    }
}
