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
use Illuminate\Database\Eloquent\Collection;

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
            defaultAddress: $defaultAddress,
            anotherAddresses: new Collection([$anotherAddress]),
            deliveryDate: $deliveryDate,
            shippingFee: $shippingFee,
            subtotal: $subtotal,
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
        $address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->mockAction(
            ProcessCheckout::class,
            [$this->user, $address->id],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('checkout.index'))
            ->post(route('checkout.store'), ['address_id' => $address->id]);

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