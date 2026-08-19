<?php

namespace Tests\Feature\Review;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * isOwnedBy
     */
    public function test_it_returns_true_when_the_review_is_owned_by_the_user()
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);

        $isOwnedByUser = $review->isOwnedBy($user);

        $this->assertTrue($isOwnedByUser);
    }

    /**
     * hasReviewed
     */
    public function test_it_returns_true_when_the_user_has_reviewed_the_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Review::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $hasReviewed = Review::hasReviewed($user->id, $product->id);

        $this->assertTrue($hasReviewed);
    }
}
