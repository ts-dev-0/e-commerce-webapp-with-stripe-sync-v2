<?php

namespace Tests\Feature\Product;

use App\Actions\Admin\Search\SearchAllProduct;
use App\Models\Product;
use App\Queries\SearchProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchAllProductTest extends TestCase
{
    use RefreshDatabase;

    private SearchAllProduct $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new SearchAllProduct(new SearchProduct);
    }

    public function test_admin_can_search_all_products_by_keyword()
    {
        $published = Product::factory()->create([
            'name' => 'target product',
            'is_published' => true,
        ]);

        $private = Product::factory()->create([
            'name' => 'target product',
            'is_published' => false,
        ]);

        $other = Product::factory()->create([
            'name' => 'other product',
            'is_published' => true,
        ]);

        $result = $this->action->handle('target');

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($published));
        $this->assertTrue($result->contains($private));
        $this->assertFalse($result->contains($other));
    }

    public function test_admin_gets_all_products_when_keyword_is_empty()
    {
        $published = Product::factory()->create([
            'is_published' => true,
        ]);

        $private = Product::factory()->create([
            'is_published' => false,
        ]);

        $result = $this->action->handle('');

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($published));
        $this->assertTrue($result->contains($private));
    }
}
