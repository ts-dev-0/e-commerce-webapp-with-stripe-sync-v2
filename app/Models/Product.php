<?php

namespace App\Models;

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

    public function scopeNewArrivals($query, int $limit)
    {
        return $query->latest()->limit($limit);
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
}
