<?php

namespace App\Http\Controllers;

use App\Actions\ProductDetail\GetProductDetail;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;

use Inertia\Inertia;

class ProductController extends Controller
{
    public function show(Product $product, GetProductDetail $action)
    {
        $result = $action->handle($product);

        return Inertia::render('product/show', [
            'data' => ProductResource::make($product),
            'reviews' => ReviewResource::collection($result['reviews']),
            'averageRating' => $result['averageRating'],
            'stockStatus' => $result['stockStatus'],
        ]);
    }
}
