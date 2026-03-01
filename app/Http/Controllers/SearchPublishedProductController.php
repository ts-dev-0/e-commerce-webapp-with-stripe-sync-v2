<?php

namespace App\Http\Controllers;

use App\Actions\User\Search\SearchPublishedProduct;
use Inertia\Inertia;

class SearchPublishedProductController extends Controller
{
    public function __invoke(SearchPublishedProduct $action)
    {
        $data = $action->handle($keyword);

        return Inertia::render('search-product', [
            'data' => $data,
        ]);
    }
}
