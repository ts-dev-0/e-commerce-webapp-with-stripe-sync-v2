<?php

namespace Tests\Feature\Cart;

use App\Models\Cart as ModelsCart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    // products
    public function test_it_returns_products() {
        $user = User::factory()->create();
        $cart = ModelsCart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 10,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 1,
        ]);

        $result = $cart->products()->get();

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('id', $product1->id));
        $this->assertTrue($result->contains('id', $product2->id));

        $retrievedProduct1 = $result->firstWhere('id', $product1->id);
        $retrievedProduct2 = $result->firstWhere('id', $product2->id);

        $this->assertSame(10, $retrievedProduct1->pivot->quantity);
        $this->assertSame(1, $retrievedProduct2->pivot->quantity);
        $this->assertNotNull($retrievedProduct1->pivot->created_at);
        $this->assertNotNull($retrievedProduct1->pivot->updated_at);
    }

    // subtotal
    public function test_it_calculates_subtotal()
    {
        $user = User::factory()->create();
        $cart = ModelsCart::factory()->create([
            'user_id' => $user->id,
        ]);
        $product1 = Product::factory()->create(['price' => 1_000]);
        $product2 = Product::factory()->create(['price' => 2_500]);

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

        $subtotal = $cart->subtotal();

        $this->assertSame(9_500, $subtotal);
    }

    // total
    public function test_it_calculates_total_including_shipping_fee()
    {
        $user = User::factory()->create();
        $cart = ModelsCart::factory()->create([
            'user_id' => $user->id,
        ]);
        $product = Product::factory()->create(['price' => 2_000]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $total = $cart->total(800);

        $this->assertSame(6_800, $total);
    }

    // clear
    public function test_it_clears_all_items_from_the_cart()
    {
        $user = User::factory()->create();
        $cart = ModelsCart::factory()->create([
            'user_id' => $user->id,
        ]);
        $otherCart = ModelsCart::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $otherProduct = Product::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
        ]);
        $otherCartItem = CartItem::factory()->create([
            'cart_id' => $otherCart->id,
            'product_id' => $otherProduct->id,
        ]);

        $cart->clear();

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', ['id' => $otherCartItem->id]);
    }
}
