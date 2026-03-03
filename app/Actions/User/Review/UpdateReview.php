<?php

namespace App\Actions\User\Review;

use App\Models\Review;

class UpdateReview
{
    public function handle(Review $review, array $attr): Review
    {
        $review->update($attr);

        return $review->refresh();
    }
}
