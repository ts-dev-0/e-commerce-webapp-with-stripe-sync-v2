<?php

namespace Tests\Feature\Requests\Admin\Product;

use App\Http\Requests\Admin\Product\UpdateProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateProductRequestTest extends TestCase
{
    private UpdateProductRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateProductRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_name_field_is_not_exists()
    {
        $validator = Validator::make([
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_empty()
    {
        $validator = Validator::make([
            'name' => '',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_integer()
    {
        $validator = Validator::make([
            'name' => 10,
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_array()
    {
        $validator = Validator::make([
            'name' => ['hello world'],
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_more_than_255()
    {
        $validator = Validator::make([
            'name' => str_repeat('a', 256),
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_description_field_is_not_exists()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_description_is_integer()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 1,
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_description_is_over_than_5000()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => str_repeat('a', 5001),
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
    
    public function test_validation_fails_when_price_field_is_not_exists()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_price_is_string()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 'hello world',
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_price_is_less_than_1()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 0,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_price_is_minus()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => -10,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_price_field_is_more_than_1000000()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000001,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_stock_field_is_not_exists()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
    
    public function test_validation_fails_when_stock_is_string()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 'test product',
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_stock_is_less_than_0()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => -1,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_stock_is_more_than_100()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 101,
            'manufacturer' => 'Test manufacturer',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_manufacturer_field_is_not_exists()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_manufacturer_is_integer()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 10,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_manufacturer_is_more_than_255()
    {
        $validator = Validator::make([
            'name' => 'Test product',
            'description' => 'This is great product',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => str_repeat('a', 256),
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
