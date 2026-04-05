<?php

namespace App\Actions\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ViewOrderHistory
{
    public function handle(User $user): Collection
    {
        return  $user->orders()
            ->with('items')
            ->orderByDesc('created_at')
            ->get();
    }
}
