<?php

namespace App\Http\Controllers;

use App\Actions\Cart\AddItemToCart;
use App\Actions\Cart\RemoveCartItem;
use App\Actions\Cart\UpdateCartItemQuantity;
use App\Http\Requests\CartItem\DestroyCartItemRequest;
use App\Http\Requests\CartItem\StoreCartItemRequest;
use App\Http\Requests\CartItem\UpdateCartItemRequest;

class CartItemController extends Controller
{
    public function store(StoreCartItemRequest $request, AddItemToCart $action)
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

    public function update(UpdateCartItemRequest $request, UpdateCartItemQuantity $action)
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

    public function destroy(DestroyCartItemRequest $request, RemoveCartItem $action)
    {
        $validatedData = $request->validated();
        $action->handle(
            $request->user(),
            $validatedData['product_id'],
        );

        return redirect()
            ->back()
            ->with('success', 'Item removed from cart.');
    }
}
