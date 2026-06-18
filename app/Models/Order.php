<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'ordered_at',
        'full_name',
        'postal_code',
        'prefecture',
        'city',
        'address_line',
        'phone_number',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'ordered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function addItem(Product $product, int $quantity, int $subtotal): void
    {
        $this->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'price' => $product->price,
            'subtotal' => $subtotal,
        ]);
    }

    public function scopeFilteredByPeriod(Builder $query, ?string $filter, Carbon $userCreatedAt): Builder
    {
        return match (true) {
            $filter === 'last30' => $query->where('ordered_at', '>=', now()->subDays(30)),
            $filter === 'months-3' => $query->where('ordered_at', '>=', now()->subMonths(3)),

            is_numeric($filter) && (int)$filter >= $userCreatedAt->year && (int)$filter <= now()->year
            => $query->whereYear('ordered_at', (int)$filter),

            default => $query->where('ordered_at', '>=', now()->subMonths(3)),
        };
    }
}
