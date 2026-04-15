<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Actions\Search\SearchPublishedProducts;
use App\Http\Requests\Search\SearchPublishedProductRequest;
use App\Http\Resources\ProductResource;

class SearchPublishedProductsController extends Controller
{
    public function __invoke(SearchPublishedProductRequest $request, SearchPublishedProducts $action)
    {
        $validatedData = $request->validated();
        $products = $action->handle($validatedData['keyword']);

        return Inertia::render('search-product', [
            'products' => ProductResource::collection($products),
            'keyword' => $validatedData['keyword'],
        ]);
    }
}
