<?php

namespace App\Actions\User\Review;

use App\Models\Review;
use App\Models\User;

class UpdateReview
{
    public function handle(User $user, Review $review, array $data): Review
    {
        if (! $review->isOwnedBy($user)) {
            throw new \DomainException('You cannot update this review.');
        }

        $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        return $review;
    }
}
