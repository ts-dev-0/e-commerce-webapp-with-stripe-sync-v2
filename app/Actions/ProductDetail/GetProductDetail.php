<?php

namespace App\Actions\ProductDetail;

use App\Models\Product;

class GetProductDetail
{
  public function handle(Product $product): array
  {
    $reviews = $product->reviews;
    $averageReview = $reviews->avg('rating') ?? 0.0;

    return [
      'reviews' => $reviews,
      'averageReview' => $averageReview,
    ];
  }
}
