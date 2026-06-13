<?php

namespace Tests\Feature\Review;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Review\CreateReview;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class CreateReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_creates_a_review()
    {
        $product = Product::factory()->create();

        $user = User::factory()->create();
        $reviewData = [
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Good Product!',
        ];

        app(CreateReview::class)->handle($user, $reviewData);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $reviewData['product_id'],
            'rating' => $reviewData['rating'],
            'comment' => $reviewData['comment'],
        ]);
    }

    /**
     *  Exception Cases
     */

    /**
     *  Edge Cases
     */
}
