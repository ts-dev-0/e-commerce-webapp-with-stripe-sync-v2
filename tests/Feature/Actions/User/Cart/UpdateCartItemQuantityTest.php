<?php

namespace Tests\Feature\Actions\User\Cart;

use App\Actions\User\Cart\UpdateCartItemQuantity;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCartItemQuantityTest extends TestCase
{
    use RefreshDatabase;

    private UpdateCartItemQuantity $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateCartItemQuantity();
    }

    public function test_user_can_update_product_quantity()
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

        $updatedQuantity = 1;

        $this->action->handle($user, $product->id, $updatedQuantity);

        $this->assertDatabaseHas('cart_items', [
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $updatedQuantity,
            ]
        );
    }

    public function test_throws_exception_if_product_not_in_cart()
    {
        $user = User::factory()->create();

        Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $product = Product::factory()->create();

        $updatedQuantity = 1;

        $this->expectException(\InvalidArgumentException::class);

        $this->action->handle($user, $product->id, $updatedQuantity);
    }
}
