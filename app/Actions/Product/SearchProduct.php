<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class SearchProduct
{
    public function handle(string $keyword): Collection
    {
        $query = Product::query()
                ->orderByDesc('created_at')
                ->limit(15);
        
        if($keyword === '') {
            return $query->get();
        }

        return $query
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('manufacturer', 'like', "%{$keyword}%")
                    ->orWhereHas('categories', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
            })
            ->get();
    }
}
