<?php

namespace App\Http\Controllers;

use App\Actions\User\Cart\GetCart;
use App\Actions\User\Checkout\ProcessCheckout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request, GetCart $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('checkout', [
            'data' => $data,
        ]);
    }

    public function store(Request $request, ProcessCheckout $action)
    {
        $action->handle($request->user());
    }
}
