<?php

namespace Tests\Feature\Review;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Review\GetUserReviews;
use App\Models\Review;
use App\Models\User;

class GetUserReviewsTest extends TestCase
{
    use RefreshDatabase;

    private GetUserReviews $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new GetUserReviews();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_their_own_reviews(): void
    {
        $otherUser = User::factory()->create();

        Review::factory()->count(3)->create([
            'user_id' => $this->user->id,
        ]);

        Review::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $result = $this->action->handle($this->user);

        $this->assertCount(3, $result);

        foreach ($result as $review) {
            $this->assertEquals($this->user->id, $review->user_id);
        }
    }

    public function test_it_returns_empty_collection_when_user_has_no_reviews(): void
    {
        $result = $this->action->handle($this->user);

        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    public function test_it_does_not_include_other_users_reviews(): void
    {
        $otherUser = User::factory()->create();

        $ownReview = Review::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $otherReview = Review::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $result = $this->action->handle($this->user);

        $this->assertTrue($result->contains($ownReview));
        $this->assertFalse($result->contains($otherReview));
    }

    public function test_it_returns_reviews_in_latest_order(): void
    {
        Review::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => now()->subDays(2),
        ]);

        $new = Review::factory()->create([
            'user_id' => $this->user->id,
            'created_at' => now(),
        ]);

        $result = $this->action->handle($this->user);

        $this->assertEquals($new->id, $result->first()->id);
    }
}
