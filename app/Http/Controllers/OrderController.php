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

        return Inertia::render('account/order/index', [
            'orders' => OrderResource::collection($orderHistory->orders),
            'years' => $orderHistory->availableYears,
        ]);
    }

    public function cancel(CancelOrder $action, Order $order)
    {
        $this->authorize('cancel', $order);
        $action->handle($order);

        return to_route('account.orders.cancel.success', $order->id)
            ->with('success', 'Order has been cancelled.');
    }

    public function success(Order $order)
    {
        return Inertia::render('account/order/cancel/success', [
            'orderNumber' => $order->order_number,
        ]);
    }
}
