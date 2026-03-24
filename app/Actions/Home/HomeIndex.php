<?php

namespace App\Actions\Home;

use App\Models\Product;
use Illuminate\Support\Collection;

class HomeIndex
{
    private const LIMIT = 15;

    public function handle(): Collection
    {
        return Product::newArrivals(self::LIMIT)->get();
    }
}
