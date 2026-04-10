<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Actions\Order\CancelOrder;
use App\Actions\Order\ViewOrderHistory;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Requests\User\Order\CancelOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(IndexOrderRequest $request, ViewOrderHistory $action)
    {
        $validatedData = $request->validated();
        $data = $action->handle($request->user(), $validatedData['timeFilter'] ?? null);

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
