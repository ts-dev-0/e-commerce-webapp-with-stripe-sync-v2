<?php

namespace Tests\Feature\Actions;

use App\Actions\Product\SearchProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchProductTest extends TestCase
{
    use RefreshDatabase;

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

        $action = new SearchProduct();

        $results = $action->handle('Apple');

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

        $action = new SearchProduct();

        $results = $action->handle('Apple');

        $this->assertTrue(
            $results->pluck('id')->contains($appleProduct->id)
        );

        $this->assertFalse(
            $results->pluck('id')->contains($samsungProduct->id)
        );
    }
}
