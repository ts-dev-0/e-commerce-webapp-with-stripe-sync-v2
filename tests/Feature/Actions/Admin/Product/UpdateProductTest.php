<?php

namespace Tests\Feature\Actions\Admin\Product;

use App\Actions\Admin\Product\UpdateProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    private UpdateProduct $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateProduct();
    }

    public function test_it_updates_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old description',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'Old manufacturer',
        ]);

        $data = [
            'name' => 'New Name',
            'description' => 'New description',
            'price' => 2000,
            'stock' => 20,
            'manufacturer' => 'New manufacturer',
        ];

        $updated = $this->action->handle($product, $data);

        $this->assertEquals('New Name', $updated->name);
        $this->assertEquals(2000, $updated->price);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'description' => 'New description',
            'price' => 2000,
            'stock' => 20,
            'manufacturer' => 'New manufacturer',
        ]);
    }

}
