<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Order;
use App\Actions\User\Order\CancelOrder;
use App\Actions\User\Order\ViewOrderHistory;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // index
    public function test_authenticated_user_can_view_order_history()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Order::factory()->create([
            'user_id' => $user->id,
        ]);
        Order::factory()->create([
            'user_id' => $user->id,
        ]);


        $orders = Order::where('user_id', $user->id)->get();

        $mock = Mockery::mock(ViewOrderHistory::class);
        $mock->shouldReceive('handle')
            ->once()
            ->with($user)
            ->andReturn($orders);

        $this->app->instance(ViewOrderHistory::class, $mock);

        $response = $this
            ->actingAs($user)
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
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $mock = Mockery::mock(CancelOrder::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with(Mockery::type(Order::class));

        $this->app->instance(CancelOrder::class, $mock);

        $response = $this
            ->actingAs($user)
            ->post(route('orders.cancel', $order));

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success', 'Order has been cancelled.');
    }

    public function test_guest_cannot_cancel_order()
    {
        $order = Order::factory()->create();

        $response = $this->post(route('orders.cancel', $order));
        
        $response->assertRedirect(route('login'));
    }
}