<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\GetCheckout;
use App\Actions\Checkout\ProcessCheckout;
use App\Actions\Stripe\CreateCheckoutSession;
use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Resources\CheckoutResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index(Request $request, GetCheckout $action)
    {
        $checkoutData = $action->handle($request->user());

        if ($checkoutData->cartItems->isEmpty()) {
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

    public function success(Request $request, ProcessCheckout $action)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->string('session_id');

        $action->handle($request->user(), $sessionId);

        return Inertia::render('checkout/success');
    }

    public function failed()
    {
        return Inertia::render('checkout/failed');
    }
}
