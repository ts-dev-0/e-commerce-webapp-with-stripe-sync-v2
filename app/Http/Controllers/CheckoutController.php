<?php

namespace App\Http\Controllers;

use App\Actions\User\Checkout\GetCheckout;
use App\Actions\User\Checkout\ProcessCheckout;
use App\Http\Resources\CheckoutResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request, GetCheckout $action)
    {
        $checkoutData = $action->handle($request->user());

        if(empty($checkoutData->cartItems)) {
            return redirect()->route('cart.index');
        }

        return Inertia::render('checkout', [
            'data' => CheckoutResource::make($checkoutData),
        ]);
    }

    public function store(Request $request, ProcessCheckout $action)
    {
        $action->handle($request->user());
    }
}
