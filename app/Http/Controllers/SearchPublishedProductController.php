<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SearchPublishedProductController extends Controller
{
    public function __invoke()
    {
        // TODO: Get product search result

        return Inertia::render('search-product');
    }
}
