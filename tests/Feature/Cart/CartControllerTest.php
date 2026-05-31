<?php

namespace Tests\Feature\Cart;

use App\DTOs\CartData;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product1;

    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->product1 = Product::factory()->create([
            'price' => 100,
        ]);
        $this->product2 = Product::factory()->create([
            'price' => 200,
        ]);
    }

    public function test_authenticated_user_can_view_cart()
    {
        $items = collect([
            new CartItem([
                'product' => $this->product1,
                'quantity' => 2,
            ]),
            new CartItem([
                'product' => $this->product2,
                'quantity' => 1,
            ]),
        ]);

        $cartData = new CartData(
            items: $items,
            subtotal: 400
        );

        $getCart = $this->mock(\App\Actions\Cart\GetCart::class);
        $getCart
            ->shouldReceive('handle')
            ->once()
            ->with($this->user)
            ->andReturn($cartData);

        $response = $this
            ->actingAs($this->user)
            ->get(route('cart.index'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_cart()
    {
        $response = $this->get(route('cart.index'));

        $response->assertRedirect(route('login'));
    }
}
