<?php

namespace App\Actions\Search;

use App\Queries\SearchProduct;
use Illuminate\Support\Collection;

class SearchPublishedProducts
{
    public function __construct(protected SearchProduct $searchProduct)
    {}

    public function handle(string $keyword): Collection
    {
        return $this->searchProduct
            ->handle($keyword)
            ->where('is_published', true)
            ->get();
    }
}
