<?php

namespace Tests\Feature\Actions\User\Review;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Review\DeleteReview;
use App\Models\Review;
use App\Models\User;

class DeleteReviewTest extends TestCase
{
    use RefreshDatabase;

    private DeleteReview $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new DeleteReview();
        $this->user = User::factory()->create();
    }

    public function test_user_can_delete_own_review()
    {
        $review = Review::factory()->create([
            'user_id' => $this->user->id,
            'rating' => 4,
            'comment' => 'test comment'
        ]);

        $this->action->handle($review);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }
}
