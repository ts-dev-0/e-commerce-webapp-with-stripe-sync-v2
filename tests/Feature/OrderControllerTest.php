<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\MocksActions;
use App\Actions\User\Order\CancelOrder;
use App\Actions\User\Order\ViewOrderHistory;
use App\Models\User;
use App\Models\Order;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

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
        $orders = Order::where('user_id', $this->user->id)->get();

        $this->mockAction(
            ViewOrderHistory::class,
            [$this->user],
            $orders,
        );

        $response = $this
            ->actingAs($this->user)
            ->get(route('orders.index'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('orders')
                 ->where('data', $orders)
        );
    }

    public function test_guest_cannot_access_orders_page()
    {
        $response = $this->get(route('orders.index'));

        $response->assertRedirect(route('login'));
    }

    // cancel
    public function test_user_can_cancel_order()
    {
        $this->mockAction(
            CancelOrder::class,
            [Mockery::type(Order::class)],
        );

        $response = $this
            ->actingAs($this->user)
            ->post(route('orders.cancel', $this->order));

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success', 'Order has been cancelled.');
    }

    public function test_guest_cannot_cancel_order()
    {
        $response = $this->post(route('orders.cancel', $this->order));
        
        $response->assertRedirect(route('login'));
    }
}