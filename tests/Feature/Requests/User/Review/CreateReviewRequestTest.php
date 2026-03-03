<?php

namespace Tests\Feature\Requests\User\Review;

use App\Http\Requests\User\Review\CreateReviewRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CreateReviewRequestTest extends TestCase
{
    use RefreshDatabase;

    private CreateReviewRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new CreateReviewRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
                'rating' => 5,
                'comment' => 'Great product.',
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->passes());
    }

    public function test_validation_passes_without_comment()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
                'rating' => 3,
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_product_does_not_exist()
    {
        $validator = Validator::make(
            [
                'product_id' => 999999,
                'rating' => 4,
                'comment' => 'Great product',
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('product_id', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_rating_is_out_of_range()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
                'rating' => 6,
                'comment' => 'Great product',
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('rating', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_comment_is_not_string()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
                'rating' => 4,
                'comment' => ['invalid'],
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('comment', $validator->errors()->toArray());
    }
}
