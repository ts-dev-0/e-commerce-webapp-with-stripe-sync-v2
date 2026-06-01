<?php

namespace Tests\Feature;

use App\Actions\ProductDetail\GetProductDetail;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class getProductDetailTest extends TestCase
{
    use RefreshDatabase;

    private GetProductDetail $action;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new GetProductDetail;
        $this->product = Product::factory()->create();
        Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 3,
        ]);
        Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 2,
        ]);
        Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 1,
        ]);
        Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 5,
        ]);
    }

    public function test_it_returns_product_detail_data()
    {
        $result = $this->action->handle($this->product);

        $this->assertArrayHasKey(
            'reviews',
            $result,
        );

        $this->assertArrayHasKey(
            'averageRating',
            $result,
        );

        $this->assertCount(4, $result['reviews']);
        $this->assertSame(2.75, $result['averageRating']);

        $this->assertInstanceOf(
            \Illuminate\Support\Collection::class,
            $result['reviews']
        );

        $this->assertInstanceOf(
            \App\Models\Review::class,
            $result['reviews']->first()
        );
    }

    public function test_it_returns_empty_reviews_and_zero_average_when_product_has_no_reviews()
    {
        $anotherProduct = Product::factory()->create();
        $result = $this->action->handle($anotherProduct);

        $this->assertArrayHasKey(
            'reviews',
            $result,
        );

        $this->assertArrayHasKey(
            'averageRating',
            $result,
        );
        $this->assertCount(0, $result['reviews']);
        $this->assertSame(0.0, $result['averageRating']);
    }
}
