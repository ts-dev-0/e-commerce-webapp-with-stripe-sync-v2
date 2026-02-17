<?php

namespace App\Actions\Admin\Search;

use App\Queries\SearchProduct;
use Illuminate\Support\Collection;

class SearchAllProduct
{
    public function __construct(protected SearchProduct $searchProduct)
    {}

    public function handle(string $keyword): Collection
    {
        return $this->searchProduct
            ->handle($keyword)
            ->get();
    }
}
