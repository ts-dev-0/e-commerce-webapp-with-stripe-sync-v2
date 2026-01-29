<?php

namespace Tests\Feature\Models\Product;

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
}
