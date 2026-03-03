<?php

namespace Tests\Feature\Actions\User\Order;

use App\Actions\User\Order\CancelOrder;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    private CancelOrder $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CancelOrder();
    }

    public function test_user_can_cancel_order_when_status_is_pending()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Pending,
        ]);

        $this->action->handle($order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'status' => OrderStatus::Canceled,
        ]);
    }

    public function test_user_can_not_cancel_order_when_status_is_paid()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Paid,
        ]);

        $this->expectException(\DomainException::class);
        $this->action->handle($order);
    }

    public function test_user_can_not_cancel_order_when_status_is_completed()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Completed,
        ]);

        $this->expectException(\DomainException::class);
        $this->action->handle($order);
    }

    public function test_user_can_not_cancel_order_when_status_is_canceled()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Canceled,
        ]);

        $this->expectException(\DomainException::class);
        $this->action->handle($order);
    }
}
