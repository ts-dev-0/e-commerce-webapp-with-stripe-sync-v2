<?php

namespace Tests\Feature\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    // index
    public function test_authenticated_user_can_view_order_history()
    {
        $queryParameter = ['timeFilter' => 'last30'];
        $orders = new Collection([
            $this->order,
        ]);

        $viewOrderHistory = $this->mock(\App\Actions\Order\ViewOrderHistory::class);
        $viewOrderHistory
            ->shouldReceive('handle')
            ->with($this->user, $queryParameter['timeFilter'])
            ->andReturn($orders);

        $response = $this
            ->actingAs($this->user)
            ->get(route('account.orders', $queryParameter));

        $response->assertOk();
    }

    public function test_guest_cannot_access_orders_page()
    {
        $response = $this->get(route('account.orders'));

        $response->assertRedirect(route('login'));
    }

    // cancel
    public function test_user_can_cancel_order()
    {

        $cancelOrder = $this->mock(\App\Actions\Order\CancelOrder::class);
        $cancelOrder
            ->shouldReceive('handle')
            ->with(Mockery::type(Order::class));

        $response = $this
            ->actingAs($this->user)
            ->patch(route('account.orders.cancel.update', $this->order->id));

        $response->assertRedirect(route('account.orders.cancel.complete', $this->order->id));
        $response->assertSessionHas('success', 'Order has been cancelled.');
    }

    public function test_guest_cannot_cancel_order()
    {
        $response = $this->patch(route('account.orders.cancel.update', $this->order->id));

        $response->assertRedirect(route('login'));
    }
}
