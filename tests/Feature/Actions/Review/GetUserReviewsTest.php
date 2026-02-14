<?php

namespace Tests\Feature\Actions\Review;

use App\Actions\Review\GetUserReviews;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserReviewsTest extends TestCase
{
    use RefreshDatabase;

    private GetUserReviews $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetUserReviews();
    }

    public function test_user_can_get_their_own_reviews(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Review::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        Review::factory()->count(2)->create([
            'user_id' => $otherUser->id,
        ]);

        $result = $this->action->handle($user);

        $this->assertCount(3, $result);

        foreach ($result as $review) {
            $this->assertEquals($user->id, $review->user_id);
        }
    }

    public function test_it_returns_empty_collection_when_user_has_no_reviews(): void
    {
        $user = User::factory()->create();

        $result = $this->action->handle($user);

        $this->assertCount(0, $result);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $result);
    }

    public function test_it_does_not_include_other_users_reviews(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownReview = Review::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherReview = Review::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $result = $this->action->handle($user);

        $this->assertTrue($result->contains($ownReview));
        $this->assertFalse($result->contains($otherReview));
    }

}
