<?php

namespace Tests\Feature\Requests\Admin\Product;

use App\Http\Requests\Admin\Product\DeleteProductRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DeleteProductRequestTest extends TestCase
{
    use RefreshDatabase;

    private DeleteProductRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new DeleteProductRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $product = Product::factory()->create();

        $validator = Validator::make([
            'product_id' => $product->id,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_product_id_field_is_not_exists()
    {
        $validator = Validator::make([], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_product_id_string()
    {
        $validator = Validator::make([
            'product_id' => 'ten',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_product_is_not_exists()
    {
        Product::factory()->create();

        $validator = Validator::make([
            'product_id' => 1000,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
