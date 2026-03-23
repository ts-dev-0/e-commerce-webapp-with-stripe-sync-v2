<?php

namespace Tests\Feature\Order;

use App\Actions\Admin\Order\GetAllOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllOrderTest extends TestCase
{
    use RefreshDatabase;

    private GetAllOrder $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetAllOrder();
    }

    public function test_admin_can_get_all_orders()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order1 = Order::factory()->for($user1)->create();
        $order2 = Order::factory()->for($user2)->create();

        $result = $this->action->handle();

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($order1));
        $this->assertTrue($result->contains($order2));
    }

}
