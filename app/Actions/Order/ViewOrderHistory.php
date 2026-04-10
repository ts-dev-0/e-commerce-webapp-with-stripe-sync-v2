<?php

namespace App\Actions\Order;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ViewOrderHistory
{
    public function handle(User $user, ?string $timeFilter): Collection
    {
        $timeFilter ??= 'last30';

        $query = $user->orders()
            ->with('items')
            ->orderByDesc('ordered_at');

        if ($timeFilter === 'last30') {

            return $query
                ->where(
                    'ordered_at',
                    '>=',
                    now()->subDays(30)
                )
                ->get();
        }

        if (preg_match('/^months-(\d+)$/', $timeFilter, $matches)) {

            return $query
                ->where(
                    'ordered_at',
                    '>=',
                    now()->subMonths((int) $matches[1])
                )
                ->get();
        }

        if (preg_match('/^year-(\d{4})$/', $timeFilter, $matches)) {

            return $query
                ->whereYear(
                    'ordered_at',
                    (int) $matches[1]
                )
                ->get();
        }

        return $query->get();
    }
}
