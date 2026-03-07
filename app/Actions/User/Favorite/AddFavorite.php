<?php

namespace App\Actions\User\Favorite;

use App\Models\User;

class AddFavorite
{
    public function handle(User $user, int $productId): void
    {
        $user->favoriteProducts()
            ->syncWithoutDetaching([$productId]);
    }
}
