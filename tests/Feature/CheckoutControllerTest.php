<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Product;
use App\Actions\User\Checkout\GetCheckout;
use App\Actions\User\Checkout\ProcessCheckout;
use App\DTOs\CheckoutData;
use App\Models\Address;
use App\Models\CartItem;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // index
    public function test_authenticated_user_can_view_checkout_page()
    {
        /** @var \APP\Models\User $user */
        $user = User::factory()->create();

        $product1 = Product::factory()->make(['price' => 100]);
        $product2 = Product::factory()->make(['price' => 200]);

        $items = collect([
            new CartItem(['product' => $product1, 'quantity' => 2]),
            new CartItem(['product' => $product2, 'quantity' => 1]),
        ]);

        $deliveryDate = [
            '2026-03-20',
            '2026-03-21',
            '2026-03-22',
            '2026-03-23',
        ];

        $addresses = collect([
            Address::factory()->make([
                'user_id' => $user->id,
                'is_default' => true,
            ]),
            Address::factory()->make([
                'user_id' => $user->id,
            ]),
        ]);

        $subtotal = 400;
        $shippingFee = 0;
        $total = $subtotal + $shippingFee;

        $cartData = new CheckoutData(
            cartItems: $items,
            subtotal: $subtotal,
            deliveryDate: $deliveryDate,
            addresses: $addresses,
            shippingFee: $shippingFee,
            total: $total,
        );

        $mock = Mockery::mock(GetCheckout::class);
        $mock->shouldReceive('handle')
            ->once()
            ->with(Mockery::on(fn($u) => $u->id === $user->id))
            ->andReturn($cartData);

        $this->app->instance(GetCheckout::class, $mock);

        $response = $this
            ->actingAs($user)
            ->get(route('checkout.index'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_checkout_page()
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect('/login');
    }

    // store
    public function test_user_can_process_checkout()
    {
        /** @var \APP\Models\User $user */
        $user = User::factory()->create();

        $mock = Mockery::mock(ProcessCheckout::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user);

        $this->app->instance(ProcessCheckout::class, $mock);

        $response = $this
            ->actingAs($user)
            ->post(route('checkout.store'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_process_checkout()
    {
        $response = $this->post(route('checkout.store'));

        $response->assertRedirect(route('login'));
    }
}