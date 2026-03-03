<?php

namespace App\Actions\User\Review;

use App\Models\Review;

class DeleteReview
{
    public function handle(Review $review): void
    {
        $review->delete();
    }
}
