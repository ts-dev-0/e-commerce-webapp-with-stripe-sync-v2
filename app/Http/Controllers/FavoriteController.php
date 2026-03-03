<?php

namespace App\Http\Controllers;

use App\Actions\User\Favorite\AddFavorite;
use App\Actions\User\Favorite\RemoveFavorite;
use App\Actions\User\Favorite\ViewFavoriteProducts;
use App\Http\Requests\User\Favorite\AddFavoriteRequest;
use App\Http\Requests\User\Favorite\RemoveFavoriteRequest;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function index(ViewFavoriteProducts $action)
    {
        $data = $action->handle($user);

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
