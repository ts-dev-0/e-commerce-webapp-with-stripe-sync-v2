<?php

namespace Tests\Feature\Actions\User\Review;

use App\Actions\User\Review\CreateReview;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateReviewTest extends TestCase
{
    use RefreshDatabase;

    private CreateReview $action;

    public function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateReview();
    }

    public function test_user_can_create_review()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $review = $this->action->handle(
            $user,
            $product->id,
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

    public function test_user_cannot_review_same_product_twice()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->action->handle(
            $user,
            $product->id,
            5,
            'Great product'
        );

        $this->assertDatabaseCount('reviews', 1);

        $this->expectException(\DomainException::class);

        $this->action->handle(
            $user,
            $product->id,
            4,
            'Second review'
        );
    }
}
