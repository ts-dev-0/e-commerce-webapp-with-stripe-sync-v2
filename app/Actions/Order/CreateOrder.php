<?php

namespace App\Actions\Order;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Stripe\Checkout\Session;

class CreateOrder
{
    public function handle(
        User $user,
        Session $session,
        Address $address
    ): Order {
        return Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount' => $session->amount_total,
            'full_name' => $address->full_name,
            'postal_code' => $address->postal_code,
            'prefecture' => $address->prefecture,
            'city' => $address->city,
            'address_line' => $address->address_line,
            'phone_number' => $address->phone_number,
            'ordered_at' => now(),
        ]);
    }
}
