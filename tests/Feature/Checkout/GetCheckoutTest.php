<?php

namespace Tests\Feature\Checkout;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Delivery\DeliveryDateService;
use App\Actions\Checkout\GetCheckout;
use App\Exceptions\EmptyCartItemException;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Tests\Traits\MocksActions;

class GetCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_checkout_data()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);

        $product = Product::factory()->create([
            'price' => 100,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $deliveryDates = [
            now()->addDay()->format('Y-m-d'),
            now()->addDays(2)->format('Y-m-d'),
        ];

        $deliveryDateService = $this->mock(DeliveryDateService::class);

        $deliveryDateService
            ->shouldReceive('generate')
            ->once()
            ->andReturn($deliveryDates);

        $checkoutData = app(GetCheckout::class)->handle($user);

        $this->assertCount(1, $checkoutData->cartItems);

        $this->assertCount(1, $checkoutData->addresses);

        $this->assertEquals(
            $deliveryDates,
            $checkoutData->deliveryDate
        );

        $this->assertEquals(0, $checkoutData->shippingFee);

        $this->assertEquals(200, $checkoutData->subtotal);

        $this->assertEquals(200, $checkoutData->total);
    }

    public function test_it_throws_empty_cart_item_exception()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->expectException(EmptyCartItemException::class);

        app(GetCheckout::class)->handle($user);
    }
}
