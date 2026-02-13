<?php

namespace App\Actions\Review;

use App\Models\Review;
use App\Models\User;

class DeleteReview
{
    public function handle(User $user, Review $review): void
    {
        if(! $review->isOwnedBy($user)) {
            throw new \DomainException('You cannnot delete this review.');
        }

        $review->delete();
    }
}
