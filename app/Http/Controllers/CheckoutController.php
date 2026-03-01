<?php

namespace App\Http\Controllers;

use App\Actions\User\Cart\GetCart;
use App\Actions\User\Checkout\ProcessCheckout;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(GetCart $action)
    {
        $data = $action->handle($user);

        return Inertia::render('checkout', [
            'data' => $data,
        ]);
    }

    public function store(ProcessCheckout $action)
    {
        $action->handle($user);
    }
}
