<?php

namespace App\Http\Controllers;

use App\Actions\User\Favorite\AddFavorite;
use App\Actions\User\Favorite\RemoveFavorite;
use App\Actions\User\Favorite\ViewFavoriteProducts;
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

    public function store(AddFavorite $action)
    {
        $action->handle($user, $product);

        return redirect()->back()->with('success', 'Added to favorite.');
    }

    public function destroy(RemoveFavorite $action)
    {
        $action->handle($user, $product);

        return redirect()->back()->with('success', 'Removed favorite.');
    }
}
