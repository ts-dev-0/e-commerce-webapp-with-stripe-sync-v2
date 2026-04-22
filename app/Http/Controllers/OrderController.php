<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Actions\Order\CancelOrder;
use App\Actions\Order\GetAvailableOrderYears;
use App\Actions\Order\ViewOrderHistory;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(IndexOrderRequest $request, ViewOrderHistory $action, GetAvailableOrderYears $getYears)
    {
        $validatedData = $request->validated();
        $orders = $action->handle($request->user(), $validatedData['timeFilter'] ?? null);
        $years = $getYears->handle($request->user());

        return Inertia::render('account/orders', [
            'orders' => OrderResource::collection($orders),
            'years' => $years,
        ]);
    }

    public function show(Order $order)
    {
        $this->authorize('cancel', $order);

        return Inertia::render('account/cancel-order', [
            'order' => OrderResource::make($order),
        ]);
    }

    public function cancel(CancelOrder $action, Order $order)
    {
        $this->authorize('cancel', $order);
        $action->handle($order);

        return redirect()
            ->route('account.orders')
            ->with('success', 'Order has been cancelled.');
    }
}
