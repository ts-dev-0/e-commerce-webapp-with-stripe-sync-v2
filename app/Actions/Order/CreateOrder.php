<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;

class CreateOrder
{
    public function handle(
        User $user,
        int $totalAmount,
        Address $address
    ): Order {
        return Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'status' => OrderStatus::Pending,
            'total_amount' => $totalAmount,
            'ordered_at' => now(),
            'full_name' => $address->full_name,
            'postal_code' => $address->postal_code,
            'prefecture' => $address->prefecture,
            'city' => $address->city,
            'address_line' => $address->address_line,
            'phone_number' => $address->phone_number,
        ]);
    }
}
