<?php

namespace App\Http\Controllers;

use App\Actions\User\Cart\AddItemToCart;
use App\Actions\User\Cart\GetCart;
use App\Actions\User\Cart\RemoveCartItem;
use App\Actions\User\Cart\UpdateCartItemQuantity;
use App\Http\Requests\User\Cart\AddItemToCartRequest;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(GetCart $action)
    {
        $data = $action->handle($user);

        return Inertia::render('cart', [
            'data' => $data,
        ]);
    }

    public function store(AddItemToCartRequest $request, AddItemToCart $action,)
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

    public function update(UpdateCartItemQuantity $action)
    {
        $action->handle($user, $product, $quantity);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated.');
    }

    public function destroy(RemoveCartItem $action)
    {
        $action->handle($user, $product);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item removed from cart.');
    }
}
