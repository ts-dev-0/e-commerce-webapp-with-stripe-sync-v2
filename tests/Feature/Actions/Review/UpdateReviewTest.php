<?php

namespace Tests\Feature\Actions\Review;

use App\Actions\Review\CreateReview;
use App\Actions\Review\UpdateReview;
use App\Models\Review;
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
        $review = Review::factory()->create([
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
