<?php

namespace App\Actions\User\Favorite;

use App\Models\Product;
use App\Models\User;

class AddFavorite
{
    public function handle(User $user, Product $product): void
    {
        $user->favoriteProducts()
            ->syncWithoutDetaching([$product->id]);
    }
}
