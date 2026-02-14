<?php

namespace App\Actions\Admin\Product;

use App\Models\Product;

class DeleteProduct
{
    public function handle(Product $product): void
    {
        $product->delete();
    }
}
