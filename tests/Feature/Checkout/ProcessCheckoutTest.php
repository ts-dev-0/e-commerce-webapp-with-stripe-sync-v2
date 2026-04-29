<?php

namespace Tests\Feature\Actions\Checkout;

use App\Actions\Checkout\ProcessCheckout;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;
use Tests\TestCase;

class ProcessCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User */
    private User $user;

    /** @var \App\Models\Product */
    private Product $product;

    /** @var \App\Models\Address */
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Models\User $user */
        $this->user = User::factory()->create();

        /** @var \App\Models\Product $product */
        $this->product = Product::factory()->create([
            'price' => 1500,
            'stock' => 10,
        ]);

        /** @var \App\Models\Address $address */
        $this->address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        /** @var \App\Models\Cart $cart */
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $cart->products()->attach($this->product->id, [
            'quantity' => 2,
        ]);
    }

    public function test_it_returns_order(): void
    {
        $session = Session::constructFrom([
            'amount_total' => 3000,
            'metadata' => [
                'address_id' => $this->address->id,
            ],
        ]);

        $action = $this->partialMock(ProcessCheckout::class, function ($mock) use ($session) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('retrieveSession')
                ->once()
                ->with('cs_test_123')
                ->andReturn($session);
        });

        $order = $action->handle(
            $this->user,
            'cs_test_123'
        );

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $this->user->id,
            'total_amount' => 3000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 1500,
            'subtotal' => 3000,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 8,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $this->product->id,
        ]);
    }
}
