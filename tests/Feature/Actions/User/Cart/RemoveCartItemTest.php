<?php

namespace Tests\Feature\Actions\User\Cart;

use App\Actions\User\Cart\RemoveCartItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemoveCartItemTest extends TestCase
{
    use RefreshDatabase;

    private RemoveCartItem $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RemoveCartItem();
    }

    public function test_user_can_remove_cart_item_from_their_own_cart()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $this->action->handle($user, $product->id);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_does_nothing_if_product_not_in_cart()
    {
        $user = User::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create();
        $anotherProduct = Product::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $anotherProduct->id,
        ]);

        $this->action->handle($user, $product->id);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }
}
