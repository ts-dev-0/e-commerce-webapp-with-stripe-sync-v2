<?php

namespace App\Actions\Order;

use App\Enums\OrderStatus;
use App\Models\Order;

class CancelOrder
{
    public function handle(Order $order): void
    {
        if(! $order->status->canCancel()) {
            throw new \DomainException('Order cannot be canceled.');
        }

        $order->status = OrderStatus::Canceled;
        $order->save();
    }
}
