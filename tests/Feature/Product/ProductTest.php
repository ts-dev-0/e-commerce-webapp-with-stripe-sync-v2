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

        $averageRating = $product->getAvarageRating();

        $this->assertEqualsWithDelta(3.67, $averageRating, 0.01);
    }
}
