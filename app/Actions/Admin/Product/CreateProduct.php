<?php

namespace App\Actions\Admin\Product;

use App\Models\Product;

class CreateProduct
{
    public function handle(array $data): Product
    {
        return Product::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'manufacturer' => $data['manufacturer'],
        ]);
    }
}
