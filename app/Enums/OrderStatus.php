<?php

namespace App\Enums;

enum OrderStatus: int {
    case Ordered = 1;
    case Paid = 2;
    case Shipped = 3;
    case Canceled = 4;
}