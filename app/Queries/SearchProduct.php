<?php

namespace App\Queries;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class SearchProduct
{
    private const PER_PAGE = 15;

    public function handle(string $keyword): Builder
    {
        $query = Product::query()
                ->orderByDesc('created_at')
                ->limit(self::PER_PAGE);

        if($keyword === '') {
            return $query;
        }

        return $query
            ->where(function ($query) use ($keyword) {
                $query
                    ->where('name', 'like', "%{$keyword}%")
                    ->orWhere('manufacturer', 'like', "%{$keyword}%")
                    ->orWhereHas('categories', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
            });
    }
}
