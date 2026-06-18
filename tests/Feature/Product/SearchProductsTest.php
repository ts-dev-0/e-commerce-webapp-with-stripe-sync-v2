<?php

namespace Tests\Feature\Product;

use App\Actions\Product\SearchProducts;
use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchProductsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_returns_products_matching_the_product_name()
    {
        $machingProduct = Product::factory()->create([
            'name' => 'iphone',
            'is_published' => true,
        ]);
        $nonMachingProduct = Product::factory()->create([
            'name' => 'macbook air',
            'is_published' => true,
        ]);

        $query = 'iphone';
        $result = app(SearchProducts::class)->handle($query);

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($machingProduct));
        $this->assertFalse($result->contains($nonMachingProduct));
    }

    public function test_it_returns_products_maching_the_manufacturer()
    {
        $machingProduct = Product::factory()->create([
            'manufacturer' => 'Apple',
            'is_published' => true,
        ]);
        $nonMachingProduct = Product::factory()->create([
            'manufacturer' => 'Google',
            'is_published' => true,
        ]);

        $query = 'Apple';
        $result = app(SearchProducts::class)->handle($query);

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($machingProduct));
        $this->assertFalse($result->contains($nonMachingProduct));
    }

    public function test_it_returns_products_maching_the_categories()
    {
        $machingProduct = Product::factory()->create([
            'is_published' => true,
        ]);
        $nonMachingProduct = Product::factory()->create([
            'is_published' => true,
        ]);

        $machingCategory = Category::factory()->create([
            'name' => 'SmartPhone',
        ]);
        $machingProduct->categories()->attach($machingCategory);

        $query = 'Smart';
        $result = app(SearchProducts::class)->handle($query);

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($machingProduct));
        $this->assertFalse($result->contains($nonMachingProduct));
    }

    public function test_it_returns_only_published_products()
    {
        $machingProduct = Product::factory()->create([
            'name' => 'iphone 17',
            'is_published' => true,
        ]);
        $nonMachingProduct = Product::factory()->create([
            'name' => 'iphone 20',
            'is_published' => false,
        ]);

        $query = 'iphone 17';
        $result = app(SearchProducts::class)->handle($query);

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($machingProduct));
        $this->assertFalse($result->contains($nonMachingProduct));
    }

    /**
     *  Exception Cases
     */

    /**
     *  Edge Cases
     */
    public function test_it_returns_an_empty_collection_when_no_products_match_the_search_query()
    {
        $nonMachingProduct1 = Product::factory()->create([
            'name' => 'iphone 17',
            'is_published' => true,
        ]);
        $nonMachingProduct2 = Product::factory()->create([
            'name' => 'iphone 20',
            'is_published' => true,
        ]);

        $query = 'pixel';
        $result = app(SearchProducts::class)->handle($query);

        $this->assertCount(0, $result);
        $this->assertFalse($result->contains($nonMachingProduct1));
        $this->assertFalse($result->contains($nonMachingProduct2));
    }

    public function test_it_limits_the_search_results_to_fifteen_products(): void
    {
        Product::factory()
            ->count(20)
            ->create([
                'name' => 'iPhone 17',
                'is_published' => true,
            ]);

        $result = app(SearchProducts::class)->handle('iPhone');

        $this->assertCount(15, $result);
    }
}
