<?php

namespace App\Http\Controllers;

use App\Actions\Product\SearchProducts;
use App\Http\Requests\Search\SearchProductsRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use Inertia\Inertia;

class ShopController extends Controller
{
    private const Limit = 15;

    public function index()
    {
        $newArrivalProducts = Product::newArrivals(self::Limit)->get();

        return Inertia::render('home', [
            'products' => ProductResource::collection($newArrivalProducts),
        ]);
    }

    public function show(Product $product)
    {
        $reviews = $product->getLatestReviewsWithUser();

        $averageRating = $product->getAverageRating();

        return Inertia::render('product/show', [
            'product' => ProductResource::make($product),
            'reviews' => ReviewResource::collection($reviews),
            'averageRating' => $averageRating,
        ]);
    }

    public function searchProducts(SearchProductsRequest $request, SearchProducts $action)
    {
        $validatedData = $request->validated();
        $products = $action->handle($validatedData['keyword']);

        return Inertia::render('search-product', [
            'products' => ProductResource::collection($products),
            'keyword' => $validatedData['keyword'],
        ]);
    }
}
