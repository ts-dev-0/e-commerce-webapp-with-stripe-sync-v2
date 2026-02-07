<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class SearchProduct
{
    public function handle(string $keyword): Collection
    {
        return Product::query()
            ->where('name', 'like', '%' . $keyword . '%')
            ->get();
    }
}
