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

    public function items()
    {
        return $this->hasMany(CartItem::class);
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

    public function removeProduct(Product $product): void
    {
        $this->products()->detach($product->id);
    }

    public function checkout(): Order
    {
        $products = $this->products;

        if($products->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }

        $totalAmount = $products->sum(function ($product) {
            return $product->price * $product->pivot->quantity;
        });

        $order = Order::create([
            'user_id'      => $this->user_id,
            'total_amount' => $totalAmount,
        ]);

        foreach ($products as $product) {
            $quantity = $product->pivot->quantity;
            $price    = $product->price;

            $order->items()->create([
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'quantity'     => $quantity,
                'price'        => $price,
                'subtotal'     => $price * $quantity,
            ]);
        }

        $this->products()->detach();

        return $order;
    }
}
