<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\CartResource;
use App\Actions\Cart\ShowCart;

class CartController extends Controller
{
    public function __invoke(Request $request, ShowCart $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('cart', [
            'cart' => CartResource::make($data),
        ]);
    }
}
