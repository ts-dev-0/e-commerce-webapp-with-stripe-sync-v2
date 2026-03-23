<?php

namespace Tests\Feature\Product;


use App\Models\Category;
use App\Models\Product;
use App\Queries\SearchProduct;
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
        $iphone = Product::factory()->create([
            'name' => 'Apple iPhone',
        ]);

        $appleWatch = Product::factory()->create([
            'name' => 'Apple Watch',
        ]);

        Product::factory()->create([
            'name' => 'Samsung Galaxy',
        ]);

        $results = $this->action->handle('Apple')->get();

        $this->assertCount(2, $results);

        $this->assertTrue($results->contains('id', $iphone->id));
        $this->assertTrue($results->contains('id', $appleWatch->id));
    }

    public function test_can_search_products_by_manufacturer()
    {
        $appleProduct = Product::factory()->create([
            'manufacturer' => 'Apple',
        ]);

        $samsungProduct = Product::factory()->create([
            'manufacturer' => 'Samsung',
        ]);

        $results = $this->action->handle('Apple')->get();

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

        $results = $this->action->handle('Electronics')->get();

        $this->assertTrue($results->contains('id', $matchedProduct->id));
        $this->assertFalse($results->contains('id', $unmatchedProduct->id));
    }

    public function test_search_results_are_sorted_by_newest()
    {
        $old = Product::factory()->create([
            'created_at' => now()->subDays(2),
        ]);

        $new = Product::factory()->create([
            'created_at' => now(),
        ]);

        $results = $this->action->handle('')->get();

        $this->assertSame(
            [$new->id, $old->id],
            $results->pluck('id')->values()->all()
        );
    }

    public function test_returns_empty_collection_when_no_products_match()
    {
        Product::factory()->create([
            'name' => 'iPhone',
        ]);

        $results = $this->action->handle('NonExistingKeyword')->get();

        $this->assertCount(0, $results);
    }

    public function test_returns_latest_products_when_keyword_is_empty()
    {
        Product::factory()->count(20)->create();

        $results = $this->action->handle('')->get();

        $this->assertCount(15, $results);
    }

}
