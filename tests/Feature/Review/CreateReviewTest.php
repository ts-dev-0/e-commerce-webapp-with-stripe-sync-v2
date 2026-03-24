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

    private CreateReview $action;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = new CreateReview();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_review()
    {
        $product = Product::factory()->create();

        $review = $this->action->handle(
            $this->user,
            $product->id,
            5,
            'Great product'
        );

        $this->assertInstanceOf(Review::class, $review);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'comment' => 'Great product',
        ]);
    }

    public function test_user_cannot_review_same_product_twice()
    {
        $product = Product::factory()->create();

        $this->action->handle(
            $this->user,
            $product->id,
            5,
            'Great product'
        );

        $this->assertDatabaseCount('reviews', 1);

        $this->expectException(\DomainException::class);

        $this->action->handle(
            $this->user,
            $product->id,
            4,
            'Second review'
        );
    }
}
