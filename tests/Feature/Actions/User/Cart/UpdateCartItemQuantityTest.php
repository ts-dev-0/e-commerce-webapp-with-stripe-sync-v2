<?php

namespace Tests\Feature\Actions\User\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\User\Cart\UpdateCartItemQuantity;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class UpdateCartItemQuantityTest extends TestCase
{
    use RefreshDatabase;

    private UpdateCartItemQuantity $action;
    private User $user;
    private Cart $cart;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new UpdateCartItemQuantity();
        $this->user = User::factory()->create();
        $this->cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->product = Product::factory()->create();
    }

    public function test_user_can_update_product_quantity()
    {        
        CartItem::factory()->create([
            'cart_id' => $this->cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $updatedQuantity = 1;

        $this->action->handle($this->user, $this->product->id, $updatedQuantity);

        $this->assertDatabaseHas('cart_items', [
                'cart_id'    => $this->cart->id,
                'product_id' => $this->product->id,
                'quantity'   => $updatedQuantity,
            ]
        );
    }

    public function test_throws_exception_if_product_not_in_cart()
    {
        $updatedQuantity = 1;

        $this->expectException(\InvalidArgumentException::class);

        $this->action->handle($this->user, $this->product->id, $updatedQuantity);
    }
}
