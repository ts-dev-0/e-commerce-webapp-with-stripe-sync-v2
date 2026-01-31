<?php

namespace Tests\Feature\Models\User;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_one_cart_relation()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            $user->cart()
        );
    }

    public function test_current_cart_creates_cart_if_not_exists()
    {
        $user = User::factory()->create();

        $cart = $user->currentCart();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $cart->user_id, 'Cart user_id should match the logged in user id');

        $this->assertEquals(1, Cart::where('user_id', $user->id)->count(), 'There should be exactly one cart created for the user');
    }
}
