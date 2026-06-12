<?php

namespace Tests\Feature\Checkout;

use App\Actions\Checkout\GetCheckoutPageData;
use App\DTOs\CheckoutData;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetCheckoutPageDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_returns_checkout_page_data()
    {
        $product1 = Product::factory()->create([
            'price' => 1000,
        ]);
        $product2 = Product::factory()->create([
            'price' => 2000,
        ]);

        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem1 = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);
        $cartItem2 = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);

        $addresses = Address::factory()->create(['user_id' => $user->id]);

        $shippingFee = 0;
        $subTotal = 3000;
        $total = 3000;

        $result = app(GetCheckoutPageData::class)->handle($user);

        $this->assertInstanceOf(CheckoutData::class, $result);
        $this->assertCount(2, $result->cartItems);
        $this->assertTrue($result->cartItems->contains($cartItem1));
        $this->assertTrue($result->cartItems->contains($cartItem2));
        $this->assertCount(1, $result->addresses);
        $this->assertEquals($addresses->id, $result->addresses->first()->id);
        $this->assertEquals($shippingFee, $result->shippingFee);
        $this->assertEquals($subTotal, $result->subtotal);
        $this->assertEquals($total, $result->total);
    }

    /**
     *  Exception Cases
     */
    public function test_it_throws_empty_cart_items_exception_when_cart_items_is_empty()
    {
        $user = User::factory()->create();
        Cart::factory()->create(['user_id' => $user->id]);

        $this->expectException(\App\Exceptions\EmptyCartException::class);
        app(GetCheckoutPageData::class)->handle($user);
    }

    /**
     *  Edge Cases
     */
    public function test_it_returns_checkout_page_data_when_user_has_no_address()
    {
        $product = Product::factory()->create([
            'price' => 1000,
        ]);

        $user = User::factory()->create();
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $cartItem1 = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $shippingFee = 0;
        $subTotal = 1000;
        $total = 1000;

        $result = app(GetCheckoutPageData::class)->handle($user);

        $this->assertInstanceOf(CheckoutData::class, $result);
        $this->assertCount(1, $result->cartItems);
        $this->assertTrue($result->cartItems->contains($cartItem1));
        $this->assertCount(0, $result->addresses);
        $this->assertEquals($shippingFee, $result->shippingFee);
        $this->assertEquals($subTotal, $result->subtotal);
        $this->assertEquals($total, $result->total);
    }
}
