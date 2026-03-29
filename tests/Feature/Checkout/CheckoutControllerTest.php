<?php

namespace Tests\Feature\Checkout;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\MocksActions;
use App\Actions\Checkout\GetCheckout;
use App\Actions\Checkout\ProcessCheckout;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\DTOs\CheckoutData;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
    }

    // index
    public function test_authenticated_user_can_view_checkout_page()
    {
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
                'user_id' => $this->user->id,
                'is_default' => true,
            ]),
            Address::factory()->make([
                'user_id' => $this->user->id,
            ]),
        ]);

        $subtotal = 400;
        $shippingFee = 0;
        $total = $subtotal + $shippingFee;

        $checkoutData = new CheckoutData(
            cartItems: $items,
            subtotal: $subtotal,
            deliveryDate: $deliveryDate,
            addresses: $addresses,
            shippingFee: $shippingFee,
            total: $total,
        );

        $this->mockAction(
            GetCheckout::class,
            [$this->user],
            $checkoutData,
        );

        $response = $this
            ->actingAs($this->user)
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
        $this->mockAction(
            ProcessCheckout::class,
            [$this->user],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('checkout.index'))
            ->post(route('checkout.store'));

        $response->assertRedirect(route('checkout.success'));

        $response->assertSessionHas(
            'success',
            'Checkout successfully.'
        );
    }

    public function test_guest_cannot_process_checkout()
    {
        $response = $this->post(route('checkout.store'));

        $response->assertRedirect(route('login'));
    }
}