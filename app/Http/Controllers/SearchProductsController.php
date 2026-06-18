<?php

namespace App\Http\Controllers;

use App\Actions\Product\SearchProducts;
use Inertia\Inertia;
use App\Http\Requests\Search\SearchProductsRequest;
use App\Http\Resources\ProductResource;

class SearchProductsController extends Controller
{
    public function __invoke(SearchProductsRequest $request, SearchProducts $action)
    {
        $validatedData = $request->validated();
        $products = $action->handle($validatedData['keyword']);

        return Inertia::render('search-product', [
            'products' => ProductResource::collection($products),
            'keyword' => $validatedData['keyword'],
        ]);
    }
}
