<?php

namespace App\Actions\CartItem;

use App\Models\User;

class RemoveCartItem
{
    public function handle(User $user, int $productId): void
    {
        $user->currentCart()
            ->products()
            ->detach($productId);
    }
}
