<?php

namespace App\Actions\Favorite;

use App\Models\User;
use Illuminate\Support\Collection;

class ViewFavoriteProducts
{
    public function handle(User $user): Collection
    {
        return $user->favoriteProducts()
            ->orderByDesc('favorites.created_at')
            ->get();
    }
}
