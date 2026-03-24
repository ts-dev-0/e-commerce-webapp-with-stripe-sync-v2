<?php

namespace Tests\Feature\Checkout;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Checkout\ProcessCheckout;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class ProcessCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private ProcessCheckout $action;
    private User $user;
    private Cart $cart;
    private Product $product;
    private CartItem $cartItem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ProcessCheckout();
        $this->user = User::factory()->create();
        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->product = Product::factory()->create(['price' => 1000]);
        $this->cartItem = CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
         ]);
    }

    public function test_cart_can_be_checked_out_into_order()
    {
        $productB = Product::factory()->create(['price' => 2000]);

        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $productB->id,
            'quantity' => 2,
        ]);

        $this->action->handle($this->user);

        $this->assertDatabaseHas('orders', [
            'user_id'      => $this->user->id,
            'total_amount' => 5000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'quantity'   => 1,
            'price'      => 1000,
            'subtotal'   => 1000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $productB->id,
            'quantity'   => 2,
            'price'      => 2000,
            'subtotal'   => 4000,
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

        $this->action->handle($user);
    }

    public function test_cart_can_be_used_again_after_checkout()
    {    
        $this->action->handle($this->user);

        $newProduct = Product::factory()->create(['price' => 500]);
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $newProduct->id,
            'quantity' => 2,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'cart_id'    => $this->cart->id,
            'product_id' => $newProduct->id,
            'quantity'   => 2,
        ]);
    }

    public function test_checkout_returns_order_instance()
    {
        $order = $this->action->handle($this->user);

        $this->assertInstanceOf(Order::class, $order);
    }
}
