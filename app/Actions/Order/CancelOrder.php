<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Order;

class CancelOrder
{
    public function handle(Order $order): void
    {
        if (! $order->status->canCancel()) {
            throw new \App\Exceptions\OrderCannotBeCanceledException('Only pending orders can be canceled.');
        }

        $order->update([
            'status' => OrderStatus::Canceled,
        ]);
    }
}
