<?php

namespace Tests\Feature\Review;

use Mockery;
use Tests\TestCase;
use Tests\Traits\MocksActions;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Review\CreateReview;
use App\Actions\Review\DeleteReview;
use App\Actions\Review\GetUserReviews;
use App\Actions\Review\UpdateReview;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    // index
    public function test_authenticated_user_can_view_reviews()
    {
        $reviews = collect([
            Review::factory()->make(),
            Review::factory()->make(),
        ]);

        $this->mockAction(
            GetUserReviews::class,
            [$this->user],
            $reviews,
        );

        $response = $this
            ->actingAs($this->user)
            ->get(route('reviews.index'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('reviews')
                ->where('data', $reviews)
        );
    }

    public function test_guest_cannot_access_reviews_page()
    {
        $response = $this->get(route('reviews.index'));

        $response->assertRedirect(route('login'));
    }

    // store
    public function test_user_can_create_review()
    {
        $review = [
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => "Great product",
        ];

        $this->mockAction(
            CreateReview::class,
            [$this->user, $review['product_id'], $review['rating'], $review['comment']],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('reviews.index'))
            ->post(route('reviews.store'), $review);

        $response->assertRedirect(route('reviews.index'));

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

        $updatedReview = [
            'rating' => 4,
            'comment' => 'Updated review',
        ];

        $this->mockAction(
            UpdateReview::class,
            [Mockery::type(Review::class), $updatedReview],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('reviews.index'))
            ->put(route('reviews.update', $review), $updatedReview);

        $response->assertRedirect(route('reviews.index'));

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

        $this->mockAction(
            DeleteReview::class,
            [Mockery::type(Review::class)],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('reviews.index'))
            ->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('reviews.index'));

        $response->assertSessionHas('success', 'Review deleted.');
    }

    public function test_guest_cannot_delete_review()
    {
        $review = Review::factory()->create();

        $response = $this->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('login'));
    }
}
