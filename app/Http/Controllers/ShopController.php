<?php

namespace App\Http\Controllers;

use App\Actions\ProductDetail\GetProductDetail;
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

    public function show(Product $product, GetProductDetail $action)
    {
        $result = $action->handle($product);

        return Inertia::render('product/show', [
            'product' => ProductResource::make($product),
            'reviews' => ReviewResource::collection($result['reviews']),
            'averageRating' => $result['averageRating'],
        ]);
    }
}
