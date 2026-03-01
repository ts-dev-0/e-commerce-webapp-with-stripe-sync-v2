<?php

namespace App\Http\Controllers;

use App\Actions\User\Checkout\CancelCheckout;
use App\Actions\User\Order\ViewOrderHistory;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(ViewOrderHistory $action)
    {
        $data = $action->handle($user);

        return Inertia::render('orders', [
            'data' => $data,
        ]);
    }

    public function cancel(CancelCheckout $action)
    {
        $action->handle($order);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been cancelled.');
    }
}
