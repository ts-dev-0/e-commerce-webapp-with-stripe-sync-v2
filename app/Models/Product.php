<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'manufacturer',
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

    public function scopeNewArrivals($query, int $limit)
    {
        return $query->latest()->limit($limit);
    }
}
