<?php

namespace App\Services\Delivery;

class DeliveryDateService
{
    public function generate(int $leadTime = 2, int $days = 5): array
    {
        $startDate = now()->startOfDay()->addDays($leadTime);

        $dates = [];

        for ($i = 0; $i < $days; $i++) {
            $dates[] = $startDate->copy()->addDays($i)->toDateString();
        }

        return $dates;
    }
}