<?php

namespace Tests\Feature\Cart;

use App\Http\Requests\User\Cart\RemoveCartItemRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RemoveCartItemRequestTest extends TestCase
{
    use RefreshDatabase;

    private RemoveCartItemRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new RemoveCartItemRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $product = Product::factory()->create();

        $validator = Validator::make([
            'product_id' => $product->id,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_product_id_is_missing()
    {
        Product::factory()->create();

        $validator = Validator::make([], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('product_id', $validator->errors()->toArray());
    }
}
