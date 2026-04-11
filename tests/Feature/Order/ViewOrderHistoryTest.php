<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Order\ViewOrderHistory;
use App\Models\Order;
use App\Models\User;

class ViewOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    private ViewOrderHistory $action;
    private User $user;
    private string $timeFilter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ViewOrderHistory();
        $this->user = User::factory()->create();
        $this->timeFilter = 'last30';
    }

    public function test_user_can_view_their_order_history()
    {
        $otherUser = User::factory()->create();

        $userOrders = Order::factory()->count(2)->create([
            'user_id' => $this->user->id,
        ]);

        Order::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $orders = $this->action->handle($this->user, $this->timeFilter);

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
        $oldOrder = Order::factory()->create([
            'user_id' => $this->user->id,
            'ordered_at' => now()->subDays(2),
        ]);

        $newOrder = Order::factory()->create([
            'user_id' => $this->user->id,
            'ordered_at' => now(),
        ]);

        $orders = $this->action->handle($this->user, $this->timeFilter);

        $this->assertCount(2, $orders);

        $this->assertEquals($newOrder->id, $orders[0]->id);
        $this->assertEquals($oldOrder->id, $orders[1]->id);
    }

    public function test_returns_empty_collection_when_user_has_no_orders()
    {
        $orders = $this->action->handle($this->user, $this->timeFilter);

        $this->assertCount(0, $orders);
        $this->assertTrue($orders->isEmpty());
    }

}
