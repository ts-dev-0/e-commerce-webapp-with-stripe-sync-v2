<?php

namespace Tests\Feature\CartItem;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\CartItem\AddItemToCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class AddItemToCartTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_can_add_an_item_to_the_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock' => 10,
        ]);
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        app(AddItemToCart::class)->handle($user, $product->id, 1);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 9,
        ]);
    }

    public function test_it_can_increment_item_quantity_when_item_already_exists_in_the_cart()
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

        app(AddItemToCart::class)->handle($user, $product->id, 1);

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseCount('cart_items', 1);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 9,
        ]);
    }


    /**
     *  Exception Cases
     */
    public function test_it_throws_insufficient_stock_exception_when_stock_is_zero()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock' => 0,
        ]);
        Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->expectException(\App\Exceptions\InsufficientStockException::class);
        app(AddItemToCart::class)->handle($user, $product->id, 1);
    }
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
        app(AddItemToCart::class)->handle($user, $product->id, 10);
    }

    /**
     *  Edge Cases
     */
}
