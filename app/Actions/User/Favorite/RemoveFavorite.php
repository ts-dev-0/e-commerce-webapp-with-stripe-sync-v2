<?php

namespace App\Actions\User\Favorite;

use App\Models\User;

class RemoveFavorite
{
    public function handle(User $user, int $productId): void
    {
        $user->favoriteProducts()->detach($productId);
    }
}
