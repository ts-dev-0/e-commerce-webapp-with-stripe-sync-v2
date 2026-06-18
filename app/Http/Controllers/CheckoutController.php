<?php

namespace App\Http\Controllers;

use App\Actions\Checkout\GetCheckoutPageData;
use App\Actions\Checkout\CompleteCheckout;
use App\Actions\Stripe\CreateCheckoutSession;
use App\Http\Requests\StoreCheckoutRequest;
use App\Http\Resources\CheckoutResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request, GetCheckoutPageData $action)
    {
        try {
            $checkoutData = $action->handle($request->user());

            return Inertia::render('checkout', [
                'checkout' => CheckoutResource::make($checkoutData),
            ]);
        } catch (\App\Exceptions\EmptyCartException $e) {
            error_log($e->getMessage());

            return redirect()->route('cart.index');
        }
    }

    public function store(StoreCheckoutRequest $request, CreateCheckoutSession $action)
    {
        $url = $action->handle($request->user(), $request->validated()['address_id']);

        return Inertia::location($url);
    }

    public function success(Request $request, CompleteCheckout $action)
    {
        $action->handle(
            $request->user(),
            $request->string('session_id'),
        );

        return Inertia::render('checkout/success');
    }

    public function failed()
    {
        return Inertia::render('checkout/failed');
    }
}
