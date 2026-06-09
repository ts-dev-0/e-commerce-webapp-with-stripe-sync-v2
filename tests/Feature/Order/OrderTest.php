<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_returns_the_past_three_months_data_as_default()
    {
        $user = User::factory()->create();

        Product::factory()->create();

        $latestOrder = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->subDays(5),
        ]);
        $orderWithinRange = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->subDays(20),

        ]);

        $result = Order::filteredByPeriod(filter: null, userCreatedAt: $user->created_at)->get();

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($latestOrder));
        $this->assertTrue($result->contains($orderWithinRange));
    }

    public function test_it_returns_the_past_three_months_when_time_filter_is_months_3()
    {
        $user = User::factory()->create();

        Product::factory()->create();

        $latestOrder = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->subDays(5),
        ]);
        $orderWithinRange = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->subMonths(2),
        ]);

        $result = Order::filteredByPeriod(filter: 'months-3', userCreatedAt: $user->created_at)->get();

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($latestOrder));
        $this->assertTrue($result->contains($orderWithinRange));
    }

    public function test_it_returns_the_specified_year_data_when_time_filter_is_year()
    {
        $user = User::factory()->create([
            'created_at' => now()->setDate(2025, 1, 5),
        ]);

        Product::factory()->create();

        $latestOrder = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->setDate(2025, 12, 1),
        ]);
        $orderWithinRange = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->setDate(2025, 3, 1),
        ]);

        $result = Order::filteredByPeriod(filter: '2025', userCreatedAt: $user->created_at)->get();

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($latestOrder));
        $this->assertTrue($result->contains($orderWithinRange));
    }

    public function test_it_returns_default_three_months_data_when_time_filter_is_invalid()
    {
        $user = User::factory()->create();

        Product::factory()->create();

        $latestOrder = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->subDays(5),
        ]);
        $orderWithinRange = Order::factory()->create([
            'user_id' => $user->id,
            'ordered_at' => now()->subMonths(2),
        ]);

        $result = Order::filteredByPeriod(filter: 'Invalid filter', userCreatedAt: $user->created_at)->get();

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($latestOrder));
        $this->assertTrue($result->contains($orderWithinRange));
    }

    /**
     * Exception Cases
     */

    /**
     * Edge Cases
     */
}
