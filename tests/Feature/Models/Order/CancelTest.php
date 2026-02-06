<?php

namespace Tests\Feature\Models\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_canceled()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $order->cancel();

        $this->assertDatabaseHas('orders', [
            'id'     => $order->id,
            'status' => OrderStatus::Canceled->value,
        ]);
    }

    public function test_order_can_not_be_canceled_when_status_is_completed()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Completed,
        ]);

        $this->expectException(\DomainException::class);
        $order->cancel();
    }

    public function test_order_can_not_be_canceled_when_status_is_canceled()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::Canceled,
        ]);

        $this->expectException(\DomainException::class);
        $order->cancel();
    }
}
