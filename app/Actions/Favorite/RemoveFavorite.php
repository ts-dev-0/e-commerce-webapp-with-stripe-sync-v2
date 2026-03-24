<?php

namespace App\Actions\Favorite;

use App\Models\User;

class RemoveFavorite
{
    public function handle(User $user, int $productId): void
    {
        $user->favoriteProducts()->detach($productId);
    }
}
