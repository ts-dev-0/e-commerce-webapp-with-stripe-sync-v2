<?php

namespace Tests\Feature\Requests\User\Order;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CancelOrderAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_cancel_own_order()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('orders.cancel', $order));

        $response->assertStatus(302);
    }

    public function test_user_cannot_cancel_other_users_order()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('orders.cancel', $order));

        $response->assertForbidden();
    }
}
