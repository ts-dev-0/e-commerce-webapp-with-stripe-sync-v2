<?php

namespace Tests\Feature;

use App\Actions\User\Cart\GetCart;
use Tests\TestCase;
use Mockery;
use App\Models\User;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // index
    public function test_authenticated_user_can_view_cart()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product1 = Product::factory()->create([
            'is_published' => 1,
        ]);
        $product2 = Product::factory()->create([
            'is_published' => 0,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);

        $data = [
            [
                'product' => $product1->toArray(),
                'quantity' => 2,
            ],
            [
                'product' => $product2->toArray(),
                'quantity' => 1,
            ]
            ];
            
        $mock = Mockery::mock(GetCart::class);
        $mock->shouldReceive('handle')
            ->once()
            ->with($user)
            ->andReturn($data);

        $this->app->instance(GetCart::class, $mock);

        $response = $this->actingAs($user)->get('/cart');

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('cart')
                 ->where('data', $data)
        );
    }

    public function test_guest_cannot_access_cart()
    {
        $response = $this->get('/cart');

        $response->assertRedirect('/login');
    }
}