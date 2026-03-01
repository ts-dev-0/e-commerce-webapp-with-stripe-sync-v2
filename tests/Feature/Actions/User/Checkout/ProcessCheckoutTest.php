<?php

namespace Tests\Feature\Actions\User\Checkout;

use App\Actions\User\Checkout\ProcessCheckout;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private ProcessCheckout $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ProcessCheckout();
    }

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

        $this->action->handle($cart);

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

        $this->action->handle($cart);
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

        $this->action->handle($cart);

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

        $order = $this->action->handle($cart);

        $this->assertInstanceOf(Order::class, $order);
    }
}
