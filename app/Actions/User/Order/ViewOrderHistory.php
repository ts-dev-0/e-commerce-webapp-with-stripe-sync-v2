<?php

namespace App\Actions\User\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ViewOrderHistory
{
    public function handle(User $user): Collection
    {
        return $user->orders()
            ->orderByDesc('created_at')
            ->get();
    }
}
