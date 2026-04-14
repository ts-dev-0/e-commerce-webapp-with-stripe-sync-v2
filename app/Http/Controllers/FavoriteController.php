<?php

namespace App\Http\Controllers;

use App\Actions\Favorite\AddFavorite;
use App\Actions\Favorite\RemoveFavorite;
use App\Actions\Favorite\ViewFavoriteProducts;
use App\Http\Requests\Favorite\AddFavoriteRequest;
use App\Http\Requests\Favorite\RemoveFavoriteRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function index(Request $request, ViewFavoriteProducts $action)
    {
        $data = $action->handle($request->user());

        return Inertia::render('favorite-products', [
            'data' => $data,
        ]);
    }

    public function store(AddFavoriteRequest $request, AddFavorite $action)
    {
        $validatedData = $request->validated();
        $action->handle($request->user(), $validatedData['product_id']);

        return redirect()->back()->with('success', 'Added to favorite.');
    }

    public function destroy(RemoveFavoriteRequest $request, RemoveFavorite $action)
    {
        $validatedData = $request->validated();
        $action->handle($request->user(), $validatedData['product_id']);

        return redirect()->back()->with('success', 'Removed favorite.');
    }
}
