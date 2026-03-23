<?php

namespace Tests\Feature\Product;

use App\Actions\Admin\Product\CreateProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use RefreshDatabase;

    private CreateProduct $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateProduct();
    }

    public function test_it_creates_product(): void
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ];

        $product = $this->action->handle($data);

        $this->assertInstanceOf(Product::class, $product);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Test manufacturer',
        ]);
    }
}
