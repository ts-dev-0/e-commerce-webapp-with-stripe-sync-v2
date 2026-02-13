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

        $this->action->handle($user, $review);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_user_cannnot_delete_others_review()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $owner->id,
            'rating' => 3,
            'comment' => 'original comment',
        ]);

        $this->expectException(\DomainException::class);

        $this->action->handle($otherUser, $review, [
            'rating' => 5,
            'comment' => 'hacked comment',
        ]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 3,
            'comment' => 'original comment',
        ]);
    }
}
