<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\ProductDetail\getProductDetail;
use App\Models\Product;
use App\Models\Review;

class getProductDetailTest extends TestCase
{
    use RefreshDatabase;

    private getProductDetail $action;
    private Product $product;
    private Review $review1;
    private Review $review2;
    private Review $review3;
    private Review $review4;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new getProductDetail();
        $this->product = Product::factory()->create();
        $this->review1 = Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 3,
        ]);
        $this->review2 = Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 2,
        ]);
        $this->review3 = Review::factory()->create([
            'product_id' => $this->product->id,
            'rating' => 1,
        ]);
        $this->review4 = Review::factory()->create([
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
            'averageReview',
            $result,
        );

        $this->assertCount(4, $result['reviews']);
        $this->assertSame(2.75, $result['averageReview']);

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
            'averageReview',
            $result,
        );
        $this->assertCount(0, $result['reviews']);
        $this->assertSame(0.0, $result['averageReview']);
    }
}
