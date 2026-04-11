<?php

namespace App\Actions\ProductDetail;

use App\Models\Product;

class GetProductDetail
{
  public function handle(Product $product): array
  {
    $stockStatus = $product->stockStatus();

    $reviews = $product
      ->reviews()
      ->with('user:id,name')
      ->latest()
      ->get();

    $averageRating = $reviews->avg('rating') ?? 0.0;

    return [
      'reviews' => $reviews,
      'averageRating' => $averageRating,
      'stockStatus' => $stockStatus,
    ];
  }
}
