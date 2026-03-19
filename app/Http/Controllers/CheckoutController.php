<?php

namespace App\Http\Controllers;

use App\Actions\User\Checkout\GetCheckout;
use App\Actions\User\Checkout\ProcessCheckout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request, GetCheckout $action)
    {
        $data = $action->handle($request->user());

        if(empty($data->items)) {
            return redirect()->route('cart.index');
        }

        return Inertia::render('checkout', [
            'data' => $data,
        ]);
    }

    public function store(Request $request, ProcessCheckout $action)
    {
        $action->handle($request->user());
    }
}
