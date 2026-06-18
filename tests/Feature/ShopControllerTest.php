<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_returns_products_to_home_page()
    {
        Product::factory()->count(20)->create([
            'is_published' => true,
        ]);

        Product::factory()->create([
            'is_published' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('home')
                ->has('products', 15)
        );
    }

    public function test_product_show_page_is_displayed()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.show', $product->id));

        $response->assertOk();
    }
}
