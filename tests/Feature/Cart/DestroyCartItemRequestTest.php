<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\Cart\DestroyCartItemRequest;
use App\Models\Product;

class DestroyCartItemRequestTest extends TestCase
{
    use RefreshDatabase;

    private DestroyCartItemRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new DestroyCartItemRequest();
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
