<?php

namespace App\Http\Controllers;

use App\Actions\User\Cart\AddItemToCart;
use App\Actions\User\Cart\GetCart;
use App\Actions\User\Cart\RemoveCartItem;
use App\Actions\User\Cart\UpdateCartItemQuantity;
use App\Http\Requests\User\Cart\RemoveCartItemRequest;
use App\Http\Requests\User\Cart\UpdateCartItemQuantityRequest;
use App\Http\Requests\User\Cart\AddItemToCartRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request, GetCart $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('cart', [
            'data' => $data,
        ]);
    }

    public function store(AddItemToCartRequest $request, AddItemToCart $action)
    {
        $validatedData = $request->validated();
        $action->handle(
            $request->user(),
            $validatedData['product_id'],
            $validatedData['quantity'],
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart.');
    }

    public function update(UpdateCartItemQuantityRequest $request, UpdateCartItemQuantity $action)
    {
        $validatedData = $request->validated();
        $action->handle(
            $request->user(),
            $validatedData['product_id'],
            $validatedData['quantity'],
        );

        return redirect()
            ->back()
            ->with('success', 'Cart updated.');
    }

    public function destroy(RemoveCartItemRequest $request , RemoveCartItem $action)
    {
        $validatedData = $request->validated();
        $action->handle(
            $request->user(),
            $validatedData['product_id'],
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }
}
