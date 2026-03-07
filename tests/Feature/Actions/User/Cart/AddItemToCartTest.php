<?php

namespace Tests\Feature\Actions\User\Cart;

use App\Actions\User\Cart\AddItemToCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddItemToCartTest extends TestCase
{
    use RefreshDatabase;

    private AddItemToCart $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new AddItemToCart();
    }

    public function test_user_can_add_product_to_their_own_cart()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create();

        $quantity = 2;

        $this->action->handle($user, $product->id, $quantity);

        $this->assertDatabaseHas('cart_items', [
            'cart_id'     => $cart->id,
            'product_id'  => $product->id,
            'quantity'    => 2,
        ]);
    }

    public function test_add_product_increments_quantity_if_product_already_exists_in_cart()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $quantity = 1;

        $this->action->handle($user, $product->id, $quantity);

        $this->assertDatabaseHas('cart_items', [
            'cart_id'     => $cart->id,
            'product_id'  => $product->id,
            'quantity'    => 2,
        ]);
    }
}
