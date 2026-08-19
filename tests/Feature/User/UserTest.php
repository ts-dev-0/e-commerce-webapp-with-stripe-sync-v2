<?php

namespace Tests\Feature\User;

use App\Models\Address;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * addresses
     */
    public function test_it_returns_addresses_with_the_default_address_first()
    {
        $user = User::factory()->create();
        $oldAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
            'created_at' => now()->subMinutes(2),
        ]);
        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
            'created_at' => now(),
        ]);
        $newAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
            'created_at' => now()->subMinute(),
        ]);

        $addresses = $user->addresses()->get();

        $this->assertSame(
            [$defaultAddress->id, $oldAddress->id, $newAddress->id],
            $addresses->pluck('id')->all()
        );
    }

    /**
     * currentCart
     */
    public function test_it_creates_and_returns_the_users_current_cart()
    {
        $user = User::factory()->create();

        $cart = $user->currentCart();

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertSame($user->id, $cart->user_id);
        $this->assertDatabaseHas('carts', ['id' => $cart->id]);
    }

    /**
     * defaultAddress
     */
    public function test_it_returns_the_users_default_address()
    {
        $user = User::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);
        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $address = $user->defaultAddress;

        $this->assertSame($defaultAddress->id, $address->id);
    }

    /**
     * clearDefaultAddress
     */
    public function test_it_clears_the_users_default_address()
    {
        $user = User::factory()->create();
        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);
        $otherUserDefaultAddress = Address::factory()->create([
            'is_default' => true,
        ]);

        $user->clearDefaultAddress();

        $this->assertDatabaseHas('addresses', [
            'id' => $defaultAddress->id,
            'is_default' => false,
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $otherUserDefaultAddress->id,
            'is_default' => true,
        ]);
    }
}
