<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class SearchProduct
{
    public function handle(string $keyword): Collection
    {
        return Product::query()
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('manufacturer', 'like', "%{$keyword}%");
            })
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();
    }
}
