<?php

namespace Tests\Feature\CartItem;

use App\Actions\CartItem\UpdateCartItemQuantity;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCartItemQuantityTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_can_update_cart_item_quantity()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'stock' => 10,
        ]);
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        app(UpdateCartItemQuantity::class)->handle($user, $product->id, 2);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    /**
     *  Exception Cases
     */
    public function test_it_throws_insufficient_stock_exception_when_request_quantity_exceeds_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock' => 8,
        ]);
        Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->expectException(\App\Exceptions\InsufficientStockException::class);
        app(UpdateCartItemQuantity::class)->handle($user, $product->id, 10);
    }

    public function test_it_throws_cart_item_not_found_exception_when_cart_item_is_null()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->expectException(\App\Exceptions\CartItemNotFoundException::class);
        app(UpdateCartItemQuantity::class)->handle($user, $product->id, 1);
    }

    /**
     *  Edge Cases
     */
}
