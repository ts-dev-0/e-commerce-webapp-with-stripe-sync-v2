<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('cart');
    }

    public function store()
    {
        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart.');
    }

    public function update()
    {
        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function destroy()
    {
        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }
}
