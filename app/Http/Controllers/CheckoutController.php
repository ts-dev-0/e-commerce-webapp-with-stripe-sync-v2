<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\GetCheckout;
use App\Actions\Checkout\ProcessCheckout;
use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Resources\CheckoutResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request, GetCheckout $action)
    {
        $checkoutData = $action->handle($request->user());

        if($checkoutData->cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return Inertia::render('checkout', [
            'data' => CheckoutResource::make($checkoutData),
        ]);
    }

    public function store(StoreCheckoutRequest $request, ProcessCheckout $action)
    {
        $action->handle($request->user());

        return redirect()
            ->route('checkout.success')
            ->with('success', 'Checkout successfully.');
    }

    public function success()
    {
        return inertia('checkout/success');
    }

    public function failed()
    {
        return inertia('checkout/failed');
    }
}
