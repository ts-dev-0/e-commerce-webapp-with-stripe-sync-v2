<?php

namespace Tests\Feature\Actions;

use App\Actions\Product\SearchProduct;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchProductTest extends TestCase
{
    use RefreshDatabase;

    private SearchProduct $action;

    public function setUp(): void
    {
        parent::setUp();
        $this->action = new SearchProduct();
    }

    public function test_can_search_products_by_keyword()
    {
        Product::factory()->create([
            'name' => 'Apple iPhone',
        ]);

        Product::factory()->create([
            'name' => 'Apple Watch',
        ]);

        Product::factory()->create([
            'name' => 'Samsung Galaxy',
        ]);

        $results = $this->action->handle('Apple');

        $this->assertCount(2, $results);

        $this->assertTrue(
            $results->pluck('name')->contains('Apple iPhone')
        );

        $this->assertTrue(
            $results->pluck('name')->contains('Apple Watch')
        );
    }

    public function test_can_search_products_by_manufacture()
    {
        $appleProduct = Product::factory()->create([
            'name' => 'iPhone 15',
            'manufacturer' => 'Apple',
        ]);

        $samsungProduct = Product::factory()->create([
            'name' => 'Galaxy S24',
            'manufacturer' => 'Samsung',
        ]);

        $results = $this->action->handle('Apple');

        $this->assertTrue(
            $results->pluck('id')->contains($appleProduct->id)
        );

        $this->assertFalse(
            $results->pluck('id')->contains($samsungProduct->id)
        );
    }

    public function test_can_search_products_by_category_name()
    {
        $category = Category::factory()->create([
            'name' => 'Electronics',
        ]);

        $matchedProduct = Product::factory()->create([
            'name' => 'iPhone',
        ]);

        $unmatchedProduct = Product::factory()->create([
            'name' => 'T-Shirt',
        ]);

        $matchedProduct->categories()->attach($category->id);

        $results = $this->action->handle('Electronics');

        $this->assertTrue(
            $results->contains(fn ($product) => $product->id === $matchedProduct->id)
        );

        $this->assertFalse(
            $results->contains(fn ($product) => $product->id === $unmatchedProduct->id)
        );
    }
}
