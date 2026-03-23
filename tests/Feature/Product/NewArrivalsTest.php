<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewArrivalsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // 時刻の固定
        Carbon::setTestNow('2026-01-10 12:00:00');
    }

    public function tearDown(): void
    {
        // 時刻のリセット
        Carbon::setTestNow();
        parent::tearDown();   
    }

    /**
     * Happy Path
     */
    public function test_new_arrivals_returns_specified_number_of_products()
    {
        $oldProduct = Product::factory()->create([
            'created_at' => now()->subDays(2)
        ]);

        $middleProduct = Product::factory()->create([
            'created_at' => now()->subDays()
        ]);

        $newProduct = Product::factory()->create([
            'created_at' => now()
        ]);

        $results = Product::newArrivals(2)->get();

        $this->assertCount(2, $results);

        $this->assertTrue($results->contains($middleProduct));
        $this->assertTrue($results->contains($newProduct));

        $this->assertFalse($results->contains($oldProduct));

        $this->assertSame(
            [$newProduct->id, $middleProduct->id],
            $results->pluck('id')->all()
        );
    }

    /**
     * Edge Case
     */
    public function test_new_arrivals_returns_all_products_when_less_than_limit()
    {
        $product = Product::factory()->create([
            'created_at' => now()
        ]);

        $results = Product::newArrivals(2)->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($product));
    }

    public function test_new_arrivals_returns_all_products_when_equal_to_limit()
    {
        $older = Product::factory()->create([
            'created_at' => now()->subDay()
        ]);

        $newer = Product::factory()->create([
            'created_at' => now()
        ]);

        $results = Product::newArrivals(2)->get();

        $this->assertSame(
            [$newer->id, $older->id],
            $results->pluck('id')->all()
        );
    }

    public function test_new_arrivals_returns_only_latest_product_when_limit_is_one()
    {
        Product::factory()->create([
            'created_at' => now()->subDays(2)
        ]);

        $latest = Product::factory()->create([
            'created_at' => now()
        ]);

        $results = Product::newArrivals(1)->get();

        $this->assertCount(1, $results);
        $this->assertSame($latest->id, $results->first()->id);
    }

    public function test_new_arrivals_returns_empty_collection_when_no_products_exist()
    {
        $results = Product::newArrivals(3)->get();

        $this->assertCount(0, $results);
    }
}
