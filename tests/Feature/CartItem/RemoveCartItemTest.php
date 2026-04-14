<?php

namespace Tests\Feature\CartItem;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Cart\RemoveCartItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class RemoveCartItemTest extends TestCase
{
    use RefreshDatabase;

    private RemoveCartItem $action;
    private User $user;
    private Cart $cart;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RemoveCartItem();
        $this->user = User::factory()->create();
        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->product = Product::factory()->create();
    }

    public function test_user_can_remove_cart_item_from_their_own_cart()
    {
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
        ]);

        $this->action->handle($this->user, $this->product->id);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_does_nothing_if_product_not_in_cart()
    {
        $anotherProduct = Product::factory()->create();

        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $anotherProduct->id,
        ]);

        $this->action->handle($this->user, $this->product->id);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
        ]);
    }
}
