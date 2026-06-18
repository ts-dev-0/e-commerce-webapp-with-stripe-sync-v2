<?php

namespace App\Actions\Order;

use App\Models\User;

class GetAvailableOrderYears
{
    public function handle(User $user): array
    {
        $startYear = $user->created_at->year;
        $currentYear = now()->year;

        return collect(
            range($currentYear, $startYear)
        )->map(fn($year) => [
            'label' => (string) $year,
            'value' => (string) $year,
        ])->toArray();
    }
}
