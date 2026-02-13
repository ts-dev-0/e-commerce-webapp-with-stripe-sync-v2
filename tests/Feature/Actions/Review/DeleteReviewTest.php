<?php

namespace Tests\Feature\Actions\Review;

use App\Actions\Review\DeleteReview;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteReviewTest extends TestCase
{
    use RefreshDatabase;

    private DeleteReview $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteReview();
    }

    public function test_user_can_delete_own_review()
    {
        $user = User::factory()->create();
        $review = Review::factory()->create([
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'test comment'
        ]);

        $this->action->handle($review);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }
}
