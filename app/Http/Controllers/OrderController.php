<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        return Inertia::render('orders');
    }

    public function cancel()
    {
        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been cancelled.');
    }
}
