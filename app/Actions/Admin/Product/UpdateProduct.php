<?php

namespace App\Actions\Admin\Product;

use App\Models\Product;

class UpdateProduct
{
    public function handle(Product $product, array $data): Product
    {
        $product->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'manufacturer' => $data['manufacturer'],
        ]);

        return $product;
    }
}
