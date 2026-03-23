<?php

namespace Tests\Feature\Product;

use Tests\TestCase;
use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_product_show_page_is_displayed()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.show', $product->id));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('product/show')
                 ->where('data.id', $product->id)
                 ->where('data.name', $product->name)
        );
    }
}