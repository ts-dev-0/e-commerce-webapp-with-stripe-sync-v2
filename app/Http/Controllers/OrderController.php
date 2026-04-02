<?php

namespace App\Http\Controllers;

use App\Actions\Order\CancelOrder;
use App\Actions\Order\ViewOrderHistory;
use App\Http\Requests\User\Order\CancelOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request, ViewOrderHistory $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('account/orders', [
            'data' => OrderResource::collection($data),
        ]);
    }

    public function cancel(CancelOrderRequest $request, CancelOrder $action, Order $order)
    {
        $action->handle($order);

        return redirect()
            ->route('account.orders')
            ->with('success', 'Order has been cancelled.');
    }
}
