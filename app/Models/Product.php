<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'stripe_product_id',
        'stripe_price_id',
        'name',
        'description',
        'price',
        'stock',
        'manufacturer',
        'is_published',
    ];

    protected $appends = ['stock_status'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function favoritedUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeNewArrivals(Builder $query, int $limit): Builder
    {
        return $query
            ->where('is_published', true)
            ->latest()
            ->limit($limit);
    }

    public function getStockStatusAttribute(): array
    {
        $threshold = 10;

        if ($this->stock === 0) {
            return [
                'status' => 'outOfStock',
                'label' => '在庫なし',
            ];
        } elseif ($this->stock < $threshold) {
            return [
                'status' => 'lowStock',
                'label' => "残り{$this->stock}点",
            ];
        } else {
            return [
                'status' => 'inStock',
                'label' => '在庫あり',
            ];
        }
    }

    public function getLatestReviewsWithUser(): Collection
    {
        return $this->reviews()->with('user:id,name')->latest()->get();
    }

    public function getAverageRating(): float
    {
        return (float) ($this->reviews->avg('rating') ?? 0.0);
    }

    public function hasEnoughStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }
}
