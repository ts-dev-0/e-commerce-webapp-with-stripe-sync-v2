<?php

namespace App\Actions\Review;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class CreateReview
{
    public function handle(
        User $user,
        Product $product,
        int $rating,
        string $comment,
    ): Review
    {
        $review = new Review([
            'rating' => $rating,
            'comment' => $comment,
        ]);

        $review->user()->associate($user);
        $review->product()->associate($product);
        $review->save();

        return $review;
    }
}
