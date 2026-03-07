<?php

namespace App\Actions\User\Review;

use App\Models\Review;
use App\Models\User;

class CreateReview
{
    public function handle(
        User $user,
        int $productId,
        int $rating,
        string $comment,
    ): Review
    {
        if (Review::alreadyReviewed($user->id, $productId)) {
            throw new \DomainException('Already reviewed.');
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'rating' => $rating,
            'comment' => $comment,
        ]);

        return $review;
    }
}
