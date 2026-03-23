<?php

namespace Tests\Feature\Review;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\User\Review\UpdateReview;
use App\Models\Review;
use App\Models\User;

class UpdateReviewTest extends TestCase
{
    use RefreshDatabase;

    private UpdateReview $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new UpdateReview();
        $this->user = User::factory()->create();
    }

    public function test_user_can_update_review()
    {
        $review = Review::factory()->create([
            'user_id' => $this->user->id,
            'rating' => 5,
            'comment' => 'old comment'
        ]);

        $updated = $this->action->handle($review, [
            'rating' => 2,
            'comment' => 'updated comment'
        ]);

        $this->assertInstanceOf(Review::class, $updated);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 2,
            'comment' => 'updated comment',
        ]);
    }
}
