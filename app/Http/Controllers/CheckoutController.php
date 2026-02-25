<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        // TODO Get Cart data

        return Inertia::render('checkout');
    }

    public function store()
    {
        // TODO: Execute checkout
    }
}
