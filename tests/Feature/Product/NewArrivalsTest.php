<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_new_arrivals_scope_returns_only_published_products(): void
    {
        Product::factory()->create([
            'is_published' => true,
        ]);

        Product::factory()->create([
            'is_published' => false,
        ]);

        $products = Product::newArrivals(15)->get();

        $this->assertCount(1, $products);

        $this->assertTrue($products->first()->is_published);
    }

    public function test_new_arrivals_scope_returns_latest_products(): void
    {
        $oldProduct = Product::factory()->create([
            'is_published' => true,
            'created_at' => now()->subDay(),
        ]);

        $newProduct = Product::factory()->create([
            'is_published' => true,
            'created_at' => now(),
        ]);

        $products = Product::newArrivals(15)->get();

        $this->assertEquals(
            $newProduct->id,
            $products->first()->id
        );

        $this->assertEquals(
            $oldProduct->id,
            $products->last()->id
        );
    }

    public function test_new_arrivals_scope_limits_products_count(): void
    {
        Product::factory()->count(20)->create([
            'is_published' => true,
        ]);

        $products = Product::newArrivals(15)->get();

        $this->assertCount(15, $products);
    }
}
