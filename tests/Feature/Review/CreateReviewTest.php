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
    public function test_it_throws_review_already_exists_exception()
    {
        $product = Product::factory()->create();

        $user = User::factory()->create();
        Review::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Good Product!',
        ]);
        $reviewData = [
            'product_id' => $product->id,
            'rating' => 1,
            'comment' => 'Bad Product',
        ];

        $this->expectException(\App\Exceptions\ReviewAlreadyExistsException::class);
        app(CreateReview::class)->handle($user, $reviewData);
    }

    /**
     *  Edge Cases
     */
}
