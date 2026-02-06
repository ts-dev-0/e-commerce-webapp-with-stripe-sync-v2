<?php

namespace App\Enums;

enum OrderStatus: int {
    case Pending   = 0;
    case Paid      = 1;
    case Completed = 2;
    case Canceled  = 3;

    public function canCancel(): bool
    {
        return $this === self::Pending;
    }
}