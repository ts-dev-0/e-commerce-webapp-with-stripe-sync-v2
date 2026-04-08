<?php

namespace Tests\Feature\Checkout;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Delivery\DeliveryDateService;
use App\Actions\Checkout\GetCheckout;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class GetCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product1;
    private Product $product2;
    private Cart $cart;
    private Address $defaultAddress;
    private Address $anotherAddress;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product1 = Product::factory()->create([
            'price' => 100,
        ]);
        $this->product2 = Product::factory()->create([
            'price' => 200,
        ]);
        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product1->id,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product2->id,
            'quantity' => 5,
        ]);
        $this->defaultAddress = Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);
    
        $this->anotherAddress = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_get_checkout_data()
    {
        $this->mockDeliveryDateService();

        $action = app(GetCheckout::class);

        $result = $action->handle($this->user);

        // 戻り値の型の検証
        $this->assertInstanceOf(\App\DTOs\CheckoutData::class, $result);
        // cartItemsの購入数の検証
        $this->assertCount(2, $result->cartItems);
        // defaultAddressの検証
        $this->assertEquals(
            $this->defaultAddress->id,
            $result->defaultAddress->id
        );
        // anotherAddressの検証
        $this->assertCount(1, $result->anotherAddresses);
        // deliveryDateの検証
        $this->assertEquals(
            ['2026-04-10'],
            $result->deliveryDate
        );
        // shippingFeeの検証
        $this->assertEquals(0, $result->shippingFee);
        // subTotalの検証
        $this->assertEquals(1200, $result->subtotal);
        // totalの検証
        $this->assertEquals(1200, $result->total);
    }

    public function test_empty_checout_data_returns_empty_array()
    {
        $anotherUser = User::factory()->create();
        $this->mockDeliveryDateService();

        $action = app(GetCheckout::class);

        $result = $action->handle($anotherUser);

        $this->assertEmpty($result->cartItems);
        $this->assertEquals(0, $result->subtotal);
    }

    private function mockDeliveryDateService(array $return = ['2026-04-10']): void
    {
        $mock = \Mockery::mock(DeliveryDateService::class);

        $mock->shouldReceive('generate')
            ->once()
            ->andReturn($return);

        $this->app->instance(
            DeliveryDateService::class,
            $mock
        );
    }
}
