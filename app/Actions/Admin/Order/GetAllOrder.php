<?php

namespace App\Actions\Admin\Order;

use App\Models\Order;
use Illuminate\Support\Collection;

class GetAllOrder
{
    public function handle(): Collection
    {
        return Order::query()
            ->with(['user', 'items.product'])
            ->orderByDesc('created_at')
            ->get();
    }
}
