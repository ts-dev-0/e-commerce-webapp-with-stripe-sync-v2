<?php

namespace Tests\Feature\Requests\User\Review;

use App\Http\Requests\User\Review\UpdateReviewRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateReviewRequestTest extends TestCase
{
    private UpdateReviewRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateReviewRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $validator = Validator::make(
            [
                'rating' => 4,
                'comment' => 'Updated comment.',
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_rating_is_invalid()
    {
        $validator = Validator::make(
            [
                'rating' => 0,
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('rating', $validator->errors()->toArray());
    }
}
