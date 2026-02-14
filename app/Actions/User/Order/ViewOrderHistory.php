<?php

namespace App\Actions\User\Order;

use App\Models\User;
use Illuminate\Support\Collection;

class ViewOrderHistory
{
    public function handle(User $user): Collection
    {
        return $user->orders()
            ->orderByDesc('created_at')
            ->get();
    }
}
