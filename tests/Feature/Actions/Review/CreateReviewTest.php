<?php

namespace Tests\Feature\tests\Feature\Actions\Review;

use App\Actions\Review\CreateReview;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $action = new CreateReview();

        $review = $action->handle(
            $user,
            $product,
            5,
            'Great product'
        );

        $this->assertInstanceOf(Review::class, $review);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great product',
        ]);
    }
}
