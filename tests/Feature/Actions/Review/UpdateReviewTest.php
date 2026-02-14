<?php

namespace Tests\Feature\Actions\Review;

use App\Actions\Review\CreateReview;
use App\Actions\Review\UpdateReview;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateReviewTest extends TestCase
{
    use RefreshDatabase;

    private UpdateReview $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateReview();
    }

    public function test_user_can_update_review()
    {
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'old comment'
        ]);

        $updated = $this->action->handle($user, $review, [
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

    public function test_user_cannot_update_others_review(): void
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
