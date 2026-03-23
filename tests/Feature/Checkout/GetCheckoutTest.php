<?php

namespace Tests\Feature\Actions\User\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Delivery\DeliveryDateService;
use App\Actions\User\Checkout\GetCheckout;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class GetCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private GetCheckout $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new GetCheckout(new DeliveryDateService);
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_checkout_data()
    {
        /** @var \App\Models\User $otherUser */
        $otherUser = User::factory()->create();

        $product1 = Product::factory()->create([
            'price' => 100,
        ]);
        $product2 = Product::factory()->create([
            'price' => 200,
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $otherCart = Cart::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 5,
        ]);

        CartItem::factory()->create([
            'cart_id' => $otherCart->id,
            'product_id' => Product::factory()->create()->id,
            'quantity' => 10,
        ]);

        $address1 = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $address2 = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        Address::factory()->create();

        $result = $this->action->handle($this->user);

        $this->assertInstanceOf(\App\DTOs\CheckoutData::class, $result);
        $this->assertCount(2, $result->cartItems);

        $this->assertEqualsCanonicalizing(
            [2, 5],
            $result->cartItems->pluck('quantity')->all()
        );

        $this->assertCount(2, $result->addresses);

        $this->assertEqualsCanonicalizing(
            [$address1->id, $address2->id],
            $result->addresses->pluck('id')->all()
        );

        $this->assertEquals(1200, $result->subtotal);
    }

    public function test_empty_checout_data_returns_empty_array()
    {
        $result = $this->action->handle($this->user);

        $this->assertEmpty($result->cartItems);
        $this->assertEquals(0, $result->subtotal);
    }
}
