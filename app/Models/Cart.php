<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function addProduct(Product $product, int $quantity = 1): void
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        $existing = $this->products()
            ->where('product_id', $product->id)
            ->first();

        if($existing) {
            $this->products()->updateExistingPivot(
                $product->id,
                [
                    'quantity' => $existing->pivot->quantity + $quantity,
                ]
            );
        }

        $this->products()->attach($product->id, [
            'quantity' => $quantity,
        ]);
    }

    public function updateProductQuantity(Product $product, int $updatedQuantity): void
    {
        if($updatedQuantity < 1) {
            throw new \InvalidArgumentException('Quantity must be at least 1.');
        }

        if (! $this->products()->where('product_id', $product->id)->exists()) {
            throw new \InvalidArgumentException('Product does not exist.');
        }

        $this->products()->updateExistingPivot(
            $product->id,
            [
                'quantity' => $updatedQuantity,
            ]
        );
    }
}
