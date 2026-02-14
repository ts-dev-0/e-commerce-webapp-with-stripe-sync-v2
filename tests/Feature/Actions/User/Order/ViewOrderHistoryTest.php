<?php

namespace Tests\Feature\Actions\User\Order;

use App\Actions\User\Order\ViewOrderHistory;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    private ViewOrderHistory $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ViewOrderHistory();
    }

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

        $orders = $this->action->handle($user);

        $this->assertCount(2, $orders);

        $this->assertTrue(
            $orders->pluck('id')->contains($userOrders[0]->id)
        );

        $this->assertTrue(
            $orders->pluck('id')->contains($userOrders[1]->id)
        );
    }

    public function test_orders_are_returned_in_descending_created_at_order()
    {
        $user = User::factory()->create();

        $oldOrder = Order::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2),
        ]);

        $newOrder = Order::factory()->create([
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        $orders = $this->action->handle($user)
            ->values();

        $this->assertCount(2, $orders);

        $this->assertEquals($newOrder->id, $orders[0]->id);
        $this->assertEquals($oldOrder->id, $orders[1]->id);
    }

    public function test_returns_empty_collection_when_user_has_no_orders()
    {
        $user = User::factory()->create();

        $orders = $this->action->handle($user);

        $this->assertCount(0, $orders);
        $this->assertTrue($orders->isEmpty());
    }

}
