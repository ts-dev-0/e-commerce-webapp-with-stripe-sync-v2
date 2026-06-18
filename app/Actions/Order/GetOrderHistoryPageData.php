<?php

namespace App\Actions\Order;

use App\DTOs\OrderHistoryData;
use App\Models\User;

class GetOrderHistoryPageData
{
    public function handle(User $user, ?string $timeFilter): OrderHistoryData
    {
        $orders = $user->orders()
            ->with('items')
            ->filteredByPeriod($timeFilter ?? 'months-3', $user->created_at)
            ->orderByDesc('ordered_at')
            ->get();

        $startYear = $user->created_at->year;
        $currentYear = now()->year;
        $availableYears =  collect(
            range($currentYear, $startYear)
        )->map(fn($year) => [
            'label' => (string) $year,
            'value' => (string) $year,
        ])->toArray();

        return new OrderHistoryData(orders: $orders, availableYears: $availableYears);
    }
}
