<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  scopeNewArrivals
     */

    /**
     *  getStockStatusAttribute
     */

    /**
     *  getReviewsWithUser
     */
    public function test_get_latest_reviews_with_user_data()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Review::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 3,
        ]);

        $reviews = $product->getLatestReviewsWithUser();

        $this->assertCount(1, $reviews);
        $this->assertEquals($product->id, $reviews->first()->product_id);
        $this->assertEquals(3, $reviews->first()->rating);
        $this->assertTrue($reviews->first()->relationLoaded('user'));
        $this->assertEquals($user->name, $reviews->first()->user->name);
    }

    public function test_get_latest_reviews_with_user_returns_empty_collection_when_no_reviews_exist()
    {
        $product = Product::factory()->create();

        $reviews = $product->getLatestReviewsWithUser();

        $this->assertEmpty($reviews);
    }

    public function test_get_latest_reviews_with_user_returns_empty_when_associated_user_is_deleted()
    {
        $user = User::factory()->create();
        /** @var \App\Models\Product $product */
        $product = Product::factory()->create();
        Review::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => 3,
        ]);

        $user->forceDelete();
        $reviews = $product->getLatestReviewsWithUser();

        $this->assertCount(0, $reviews);
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_get_latest_reviews_with_user_does_not_include_reviews_from_other_products()
    {
        $user1 = User::factory()->create();
        /** @var \App\Models\Product $targetProduct */
        $targetProduct = Product::factory()->create();
        $targetReview = Review::factory()->create([
            'product_id' => $targetProduct->id,
            'user_id' => $user1->id,
        ]);

        $user2 = User::factory()->create();
        /** @var \App\Models\Product $otherProduct */
        $otherProduct = Product::factory()->create();
        $otherReview = Review::factory()->create([
            'product_id' => $otherProduct->id,
            'user_id' => $user2->id,
        ]);

        $reviews = $targetProduct->getLatestReviewsWithUser();

        $this->assertCount(1, $reviews);
        $this->assertEquals($targetReview->id, $reviews->first()->id);
        $this->assertNotEquals($otherReview->id, $reviews->first()->id);
    }

    /**
     * getAvarageRating
     */
    public function test_get_average_rating_calculates_correctly()
    {
        $product = Product::factory()->create();
        Review::factory()->create([
            'product_id' => $product->id,
            'rating' => 5,
        ]);
        Review::factory()->create([
            'product_id' => $product->id,
            'rating' => 4,
        ]);
        Review::factory()->create([
            'product_id' => $product->id,
            'rating' => 2,
        ]);

        $averageRating = $product->getAverageRating();

        $this->assertEqualsWithDelta(3.67, $averageRating, 0.01);
    }

    public function test_get_average_rating_returns_zero_when_reviews_are_empty()
    {
        $product = Product::factory()->create();

        $averageRating = $product->getAverageRating();

        $this->assertEquals(0, $averageRating);
    }

    public function test_get_average_rating_returns_empty_when_associated_user_is_deleted()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Review::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $user->forceDelete();

        $averageRating = $product->getAverageRating();

        $this->assertEquals(0, $averageRating);
    }
}
