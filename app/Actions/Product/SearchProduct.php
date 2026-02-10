<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Collection;

class SearchProduct
{
    private const PER_PAGE = 15;

    public function handle(string $keyword): Collection
    {
        $query = Product::query()
                ->orderByDesc('created_at')
                ->limit(self::PER_PAGE);
        
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
