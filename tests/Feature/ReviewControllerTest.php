<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Actions\User\Review\CreateReview;
use App\Actions\User\Review\GetUserReviews;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // index
    public function test_authenticated_user_can_view_reviews()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $reviews = Collection::make([
            Review::factory()->make(),
            Review::factory()->make(),
        ]);

        $mock = Mockery::mock(GetUserReviews::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user)
            ->andReturn($reviews);

        $this->app->instance(GetUserReviews::class, $mock);

        $response = $this
            ->actingAs($user)
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
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $mock = Mockery::mock(CreateReview::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with(
                $user,
                $product->id,
                5,
                'Great product'
            );

        $this->app->instance(CreateReview::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('reviews.index'))
            ->post(route('reviews.store'), [
                'product_id' => $product->id,
                'rating' => 5,
                'comment' => 'Great product',
            ]);

        $response->assertRedirect(route('reviews.index'));

        $response->assertSessionHas('success', 'Review posted.');
    }
}
