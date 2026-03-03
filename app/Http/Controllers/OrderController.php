<?php

namespace App\Http\Controllers;

use App\Actions\User\Checkout\CancelCheckout;
use App\Actions\User\Order\ViewOrderHistory;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request, ViewOrderHistory $action)
    {
        $data = $action->handle($request->user());

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
