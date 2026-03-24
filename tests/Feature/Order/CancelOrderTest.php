<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Order\CancelOrder;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    private CancelOrder $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new CancelOrder();
        $this->user = User::factory()->create();
    }

    public function test_user_can_cancel_order_when_status_is_pending()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::Pending,
        ]);

        $this->action->handle($order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->user->id,
            'status' => OrderStatus::Canceled,
        ]);
    }

    public function test_user_can_not_cancel_order_when_status_is_paid()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::Paid,
        ]);

        $this->expectException(\DomainException::class);
        $this->action->handle($order);
    }

    public function test_user_can_not_cancel_order_when_status_is_completed()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::Completed,
        ]);

        $this->expectException(\DomainException::class);
        $this->action->handle($order);
    }

    public function test_user_can_not_cancel_order_when_status_is_canceled()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => OrderStatus::Canceled,
        ]);

        $this->expectException(\DomainException::class);
        $this->action->handle($order);
    }
}
