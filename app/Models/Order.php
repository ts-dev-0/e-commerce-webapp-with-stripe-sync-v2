<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'ordered_at',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];
}
