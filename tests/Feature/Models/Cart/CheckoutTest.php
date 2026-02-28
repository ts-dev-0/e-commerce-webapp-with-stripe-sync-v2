<?php

namespace Tests\Feature\Models\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_can_be_checked_out_into_order()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $productA = Product::factory()->create(['price' => 1000]);
        $productB = Product::factory()->create(['price' => 2000]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $productA->id,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $productB->id,
            'quantity' => 1,
        ]);

        $cart->checkout();

        $this->assertDatabaseHas('orders', [
            'user_id'      => $user->id,
            'total_amount' => 4000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $productA->id,
            'quantity'   => 2,
            'price'      => 1000,
            'subtotal'   => 2000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $productB->id,
            'quantity'   => 1,
            'price'      => 2000,
            'subtotal'   => 2000,
        ]);

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_throws_exception_if_cart_is_empty()
    {
        $user = User::factory()->create();

        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(0, $cart->products);

        $this->expectException(\DomainException::class);

        $this->expectExceptionMessage('Cart is empty');

        $cart->checkout();
    }

    public function test_cart_can_be_used_again_after_checkout()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create(['price' => 1000]);
        
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cart->checkout();

        $newProduct = Product::factory()->create(['price' => 500]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $newProduct->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id'    => $cart->id,
            'product_id' => $newProduct->id,
            'quantity'   => 2,
        ]);
    }

    public function test_checkout_returns_order_instance()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create(['price' => 1000]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $order = $cart->checkout();

        $this->assertInstanceOf(Order::class, $order);
    }
}
