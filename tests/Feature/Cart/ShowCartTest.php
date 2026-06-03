<?php

namespace Tests\Feature\Cart;

use App\Actions\Cart\ShowCart;
use App\DTOs\CartData;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_cart_data()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 1000,
        ]);
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $cartData = app(ShowCart::class)->handle($user);

        $this->assertInstanceOf(CartData::class, $cartData);
        $this->assertEquals($cartItem->id, $cartData->items->first()->id);
        $this->assertEquals($product->id, $cartData->items->first()->product->id);
        $this->assertEquals(2000, $cartData->subtotal);
    }
}
