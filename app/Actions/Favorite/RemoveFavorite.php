<?php

namespace App\Actions\Favorite;

use App\Models\Product;
use App\Models\User;

class RemoveFavorite
{
    public function handle(User $user, Product $product): void
    {
        $user->favoriteProducts()->detach($product->id);
    }
}
