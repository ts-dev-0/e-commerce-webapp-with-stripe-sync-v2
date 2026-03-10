<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Order\GetAllOrder;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(GetAllOrder $action)
    {
        $orders = $action->handle();

        return Inertia::render('admin/order/index', [
            'data' => $orders,
        ]);
    }
}
