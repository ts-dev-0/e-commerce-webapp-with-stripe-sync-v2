<?php

namespace Tests\Feature\Checkout;

use App\DTOs\CheckoutData;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

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

        $items = new Collection([
            new CartItem(['product' => $product1, 'quantity' => 2]),
            new CartItem(['product' => $product2, 'quantity' => 1]),
        ]);

        $defaultAddress = Address::factory()->make([
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);

        $anotherAddress = Address::factory()->make([
            'user_id' => $this->user->id,
        ]);

        $deliveryDate = [
            '2026-03-20',
            '2026-03-21',
            '2026-03-22',
            '2026-03-23',
        ];

        $subtotal = 400;
        $shippingFee = 0;
        $total = $subtotal + $shippingFee;

        $checkoutData = new CheckoutData(
            cartItems: $items,
            addresses: new Collection([$defaultAddress, $anotherAddress]),
            shippingFee: $shippingFee,
            subtotal: $subtotal,
            total: $total,
        );

        $getCheckout = $this->mock(\App\Actions\Checkout\GetCheckoutPageData::class);
        $getCheckout
            ->shouldReceive('handle')
            ->once()
            ->with($this->user)
            ->andReturn($checkoutData);

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

    public function test_user_can_create_checkout_session()
    {
        $address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $checkoutUrl = 'https://checkout.stripe.com/test-session';

        $createCheckoutSession = $this->mock(\App\Actions\Stripe\CreateCheckoutSession::class);
        $createCheckoutSession
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, $address->id)
            ->andReturn($checkoutUrl);

        $response = $this
            ->actingAs($this->user)
            ->withHeaders([
                'X-Inertia' => 'true',
            ])
            ->post(route('checkout.store'), [
                'address_id' => $address->id,
            ]);

        $response->assertStatus(409);

        $response->assertHeader(
            'X-Inertia-Location',
            $checkoutUrl
        );
    }

    public function test_guest_cannot_process_checkout()
    {
        $response = $this->post(route('checkout.store'));

        $response->assertRedirect(route('login'));
    }
}
