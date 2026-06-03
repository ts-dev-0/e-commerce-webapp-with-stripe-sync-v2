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

    /**
     *  Happy Path
     */
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

    /**
     *  Edge Cases
     */
    public function test_it_returns_empty_cart_when_cart_has_no_items()
    {
        $user = User::factory()->create();
        Product::factory()->create();
        Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $cartData = app(ShowCart::class)->handle($user);

        $this->assertInstanceOf(CartData::class, $cartData);
        $this->assertTrue($cartData->items->isEmpty());
        $this->assertEquals(0, $cartData->subtotal);
    }

    public function test_it_calculates_subtotal_for_multiple_cart_items()
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create([
            'price' => 1000,
        ]);
        $product2 = Product::factory()->create([
            'price' => 3000,
        ]);
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 3,
        ]);

        $cartData = app(ShowCart::class)->handle($user);
        $this->assertInstanceOf(CartData::class, $cartData);
        $this->assertCount(2, $cartData->items);
        $this->assertEquals(11000, $cartData->subtotal);
    }

    public function test_it_returns_only_items_belonging_to_the_users_cart()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 1000,
        ]);
        $cart1 = Cart::factory()->create([
            'user_id' => $user1->id,
        ]);
        $cart2 = Cart::factory()->create([
            'user_id' => $user2->id,
        ]);
        $cartItem1 = CartItem::factory()->create([
            'cart_id' => $cart1->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart2->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartData = app(ShowCart::class)->handle($user1);

        $this->assertInstanceOf(CartData::class, $cartData);
        $this->assertCount(1, $cartData->items);
        $this->assertEquals($cartItem1->id, $cartData->items->first()->id);
        $this->assertEquals(2000, $cartData->subtotal);
    }
}
