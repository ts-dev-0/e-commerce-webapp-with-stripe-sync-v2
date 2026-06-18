<?php

namespace Tests\Feature\Order;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Order\CancelOrder;
use App\Enums\OrderStatus;
use App\Exceptions\OrderCannotBeCanceledException;
use App\Models\Order;

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_can_cancel_a_pending_order()
    {
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        app(CancelOrder::class)->handle($order);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::Canceled,
        ]);
    }

    /**
     *  Exception Cases
     */
    public function test_it_throws_order_can_not_be_canceled_excetption_when_status_is_paid()
    {
        $order = Order::factory()->create(['status' => OrderStatus::Paid]);

        $this->expectException(OrderCannotBeCanceledException::class);

        app(CancelOrder::class)->handle($order);
    }

    public function test_it_throws_order_can_not_be_canceled_excetption_when_status_is_completed()
    {
        $order = Order::factory()->create(['status' => OrderStatus::Completed]);

        $this->expectException(OrderCannotBeCanceledException::class);

        app(CancelOrder::class)->handle($order);
    }

    public function test_it_throws_order_can_not_be_canceled_excetption_when_status_is_canceled()
    {
        $order = Order::factory()->create(['status' => OrderStatus::Canceled]);

        $this->expectException(OrderCannotBeCanceledException::class);

        app(CancelOrder::class)->handle($order);
    }

    /**
     *  Edge Cases
     */
}
