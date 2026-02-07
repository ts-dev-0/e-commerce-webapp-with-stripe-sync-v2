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

}
