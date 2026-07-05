<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
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
                ->component('shop/index')
                ->has('products', 15)
        );
    }

    public function test_product_show_page_is_displayed()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.detail', $product->id));

        $response->assertOk();
    }

    public function test_user_can_search_products()
    {
        $parameter = ['keyword' => 'iphone'];

        $products = Collection::make([
            Product::factory()->make(),
            Product::factory()->make(),
        ]);

        $searchPublishedProducts = $this->mock(\App\Actions\Product\SearchProducts::class);
        $searchPublishedProducts
            ->shouldReceive('handle')
            ->with($parameter['keyword'])
            ->andReturn($products);

        $response = $this
            ->get(route('product.search', $parameter));

        $response->assertOk();
    }
}
