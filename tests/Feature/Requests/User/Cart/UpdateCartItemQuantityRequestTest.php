<?php

namespace Tests\Feature\Requests\User\Cart;

use App\Http\Requests\User\Cart\UpdateCartItemQuantityRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateCartItemQuantityRequestTest extends TestCase
{
    use RefreshDatabase;

    private UpdateCartItemQuantityRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateCartItemQuantityRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $product = Product::factory()->create();

        $validator = Validator::make([
            'product_id' => $product->id,
            'quantity' => 1,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_product_id_is_missing()
    {
        Product::factory()->create();

        $validator = Validator::make([
            'quantity' => 1,
        ], $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('product_id', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_quantity_is_missing()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('quantity', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_quantity_is_less_than_one()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
                'quantity' => 0,
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('quantity', $validator->errors()->toArray());
    }

    public function test_validation_fails_when_quantity_is_more_over_than_99()
    {
        $product = Product::factory()->create();

        $validator = Validator::make(
            [
                'product_id' => $product->id,
                'quantity' => 100,
            ],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('quantity', $validator->errors()->toArray());
    }

}
