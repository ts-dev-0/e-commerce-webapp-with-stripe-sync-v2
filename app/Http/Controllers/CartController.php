<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use App\Actions\Cart\GetCart;

class CartController extends Controller
{
    public function __invoke(Request $request, GetCart $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('cart', [
            'data' => CartResource::make($data),
        ]);
    }
}
