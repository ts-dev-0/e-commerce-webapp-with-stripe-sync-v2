<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class SearchProducts
{
    public function handle(string $keyword): Collection
    {
        return Product::query()
            ->where('is_published', true)
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('manufacturer', 'like', "%{$keyword}%")
                    ->orWhereHas('categories', function ($categoryQuery) use ($keyword) {
                        $categoryQuery->where('name', 'like', "%{$keyword}%");
                    });
            })
            ->latest()
            ->limit(15)
            ->get();
    }
}
