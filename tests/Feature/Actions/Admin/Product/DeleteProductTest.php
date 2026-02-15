<?php

namespace Tests\Feature\Actions\Admin\Product;

use App\Actions\Admin\Product\DeleteProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use RefreshDatabase;

    private DeleteProduct $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteProduct();
    }

    public function test_it_deletes_product(): void
    {
        $product = Product::factory()->create();

        $this->action->handle($product);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

}
