<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productIds = Product::pluck('id');

        Cart::all()->each(function ($cart) use ($productIds) {

            $count = rand(1, 5);

            $selectedIds = collect($productIds->random($count))->values();

            CartItem::factory()
                ->count($count)
                ->for($cart)
                ->sequence(fn ($sequence) => [
                    'product_id' => $selectedIds->get($sequence->index),
                ])
                ->create();
        });
    }
}
