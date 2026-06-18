<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Actions\Order\CancelOrder;
use App\Actions\Order\GetOrderHistoryPageData;
use App\Http\Requests\IndexOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(IndexOrderRequest $request, GetOrderHistoryPageData $action)
    {
        $validatedData = $request->validated();

        /** @var \App\DTOs\OrderHistoryData $orderHistory */
        $orderHistory = $action->handle($request->user(), $validatedData['timeFilter'] ?? null);

        return Inertia::render('account/orders', [
            'orders' => OrderResource::collection($orderHistory->orders),
            'years' => $orderHistory->availableYears,
        ]);
    }

    public function show(Order $order)
    {
        $this->authorize('cancel', $order);

        abort_unless($order->status->canCancel(), 404);

        return Inertia::render('account/cancel-order', [
            'order' => OrderResource::make($order),
        ]);
    }

    public function cancel(CancelOrder $action, Order $order)
    {
        $this->authorize('cancel', $order);
        $action->handle($order);

        return redirect()
            ->route('account.orders.cancel.complete', $order->id)
            ->with('success', 'Order has been cancelled.');
    }

    public function complete(Order $order)
    {
        return Inertia::render('account/cancel-order-complete', [
            'orderNumber' => $order->order_number,
        ]);
    }
}
