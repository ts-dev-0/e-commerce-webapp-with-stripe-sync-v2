<?php

namespace Tests\Feature\Review;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    // store
    public function test_user_can_create_review()
    {
        $review = [
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Great product',
        ];

        $createReview = $this->mock(\App\Actions\Review\CreateReview::class);
        $createReview
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, $review);

        $response = $this
            ->actingAs($this->user)
            ->from(route('product.show', $this->product->id))
            ->post(route('reviews.store'), $review);

        $response->assertRedirect(route('product.show', $this->product->id));

        $response->assertSessionHas('success', 'Review posted.');
    }

    public function test_guest_cannot_create_review()
    {
        $response = $this->post(route('reviews.store'), [
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Great product',
        ]);

        $response->assertRedirect(route('login'));
    }

    // update
    public function test_user_can_update_review()
    {
        $review = Review::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->from(route('product.show', $this->product->id))
            ->put(route('reviews.update', $review));

        $response->assertRedirect(route('product.show', $this->product->id));

        $response->assertSessionHas('success', 'Review updated.');
    }

    public function test_guest_cannot_update_review()
    {
        $review = Review::factory()->create();

        $response = $this->put(route('reviews.update', $review), [
            'rating' => 4,
            'comment' => 'Updated review',
        ]);

        $response->assertRedirect(route('login'));
    }

    // destroy
    public function test_user_can_delete_review()
    {
        $review = Review::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this
            ->actingAs($this->user)
            ->from(route('product.show', $this->product->id))
            ->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('product.show', $this->product->id));

        $response->assertSessionHas('success', 'Review deleted.');
    }

    public function test_guest_cannot_delete_review()
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('login'));
    }
}
