<?php

namespace Tests\Feature\Product;

use App\Actions\Search\SearchPublishedProducts;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Product;
use App\Queries\SearchProduct;

class SearchPublishedProductsTest extends TestCase
{
    use RefreshDatabase;

    private SearchPublishedProducts $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new SearchPublishedProducts(new SearchProduct);
    }

    public function test_user_can_published_products_by_keyword()
    {
        $publishedProduct = Product::factory()->create([
            'name' => 'published product',
            'is_published' => true,
        ]);

        $privateProduct = Product::factory()->create([
            'name' => 'published product',
            'is_published' => false,
        ]);

        $otherProduct = Product::factory()->create([
            'name' => 'other product',
            'is_published' => true,
        ]);

        $result = $this->action->handle('published');

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($publishedProduct));
        $this->assertFalse($result->contains($privateProduct));
        $this->assertFalse($result->contains($otherProduct));
    }

    public function test_user_gets_only_published_products_when_keyword_is_empty()
    {
        $published = Product::factory()->create([
            'is_published' => true,
        ]);

        $private = Product::factory()->create([
            'is_published' => false,
        ]);

        $result = $this->action->handle('');

        $this->assertCount(1, $result);
        $this->assertTrue($result->contains($published));
        $this->assertFalse($result->contains($private));
    }

}
