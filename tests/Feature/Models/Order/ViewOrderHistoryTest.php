<?php

namespace Tests\Feature\Models\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_order_history()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $userOrders = Order::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        Order::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $orders = $user->orders()->get();

        $this->assertCount(2, $orders);

        $this->assertTrue(
            $orders->pluck('id')->contains($userOrders[0]->id)
        );

        $this->assertTrue(
            $orders->pluck('id')->contains($userOrders[1]->id)
        );
    }
}
