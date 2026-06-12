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

        // CheckoutDataかどうかの検証
        $this->assertInstanceOf(CheckoutData::class, $result);
        // カート情報の検証
        $this->assertCount(2, $result->cartItems);
        $this->assertTrue($result->cartItems->contains($cartItem1));
        $this->assertTrue($result->cartItems->contains($cartItem2));
        // 配送先情報の検証
        $this->assertCount(1, $result->addresses);
        $this->assertEquals($addresses->id, $result->addresses->first()->id);
        // 配送料金の検証
        $this->assertEquals($shippingFee, $result->shippingFee);
        // 小計額の検証
        $this->assertEquals($subTotal, $result->subtotal);
        // 合計額の検証
        $this->assertEquals($total, $result->total);
    }

    /**
     *  Exception Cases
     */

    /**
     *  Edge Cases
     */
}
