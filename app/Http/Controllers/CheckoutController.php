<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\GetCheckout;
use App\Actions\Checkout\ProcessCheckout;
use App\Actions\Stripe\CreateCheckoutSession;
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
            'checkout' => CheckoutResource::make($checkoutData),
        ]);
    }

    public function store(StoreCheckoutRequest $request, CreateCheckoutSession $action)
    {
        $url = $action->handle($request->user(), $request->validated()['address_id']);

        return Inertia::location($url);
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
