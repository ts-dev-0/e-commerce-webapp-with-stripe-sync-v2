<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;

class CreateReview
{
    public function handle(User $user, array $reviewData): void
    {
        if (Review::hasReviewed($user->id, $reviewData['product_id'])) {
            throw new \App\Exceptions\ReviewAlreadyExistsException('Already reviewed.');
        }

        $user->reviews()->create([
            'product_id' => $reviewData['product_id'],
            'rating' => $reviewData['rating'],
            'comment' => $reviewData['comment'],
        ]);
    }
}
