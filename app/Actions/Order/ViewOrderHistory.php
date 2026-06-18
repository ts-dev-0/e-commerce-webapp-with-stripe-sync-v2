<?php

namespace App\Actions\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ViewOrderHistory
{
    public function handle(User $user, ?string $timeFilter): Collection
    {
        return $user->orders()
            ->with('items')
            ->filteredByPeriod($timeFilter ?? 'months-3', $user->created_at)
            ->orderByDesc('ordered_at')
            ->get();
    }
}
