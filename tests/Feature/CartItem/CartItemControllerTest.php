<?php

namespace Tests\Feature\CartItem;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product1;

    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->product1 = Product::factory()->create([
            'price' => 100,
        ]);
        $this->product2 = Product::factory()->create([
            'price' => 200,
        ]);
    }

    // store
    public function test_user_can_add_item_to_cart()
    {
        $payload = [
            'product_id' => $this->product1->id,
            'quantity' => 2,
        ];

        $addItemToCart = $this->mock(\App\Actions\CartItem\AddItemToCart::class);
        $addItemToCart
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, $this->product1->id, 2);

        $response = $this
            ->actingAs($this->user)
            ->post(route('cart.items.store'), $payload);

        $response->assertRedirect(route('cart.index'));

        $response->assertSessionHas(
            'success',
            'Product added to cart.'
        );
    }

    public function test_validation_fails_when_quantity_is_missing()
    {
        $response = $this
            ->actingAs($this->user)
            ->post(route('cart.items.store'), [
                'product_id' => $this->product1->id,
            ]);

        $response->assertSessionHasErrors('quantity');
    }

    // update
    public function test_user_can_update_cart_item_quantity()
    {
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product1->id,
        ]);

        $payload = [
            'product_id' => $this->product1->id,
            'quantity' => 3,
        ];

        $updateCartItemQuantity = $this->mock(\App\Actions\CartItem\UpdateCartItemQuantity::class);
        $updateCartItemQuantity
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, $this->product1->id, 3);

        $response = $this
            ->actingAs($this->user)
            ->from(route('cart.index'))
            ->patch(
                route('cart.items.update', $cartItem->id),
                $payload
            );

        $response->assertRedirect(route('cart.index'));

        $response->assertSessionHas(
            'success',
            'Cart updated.'
        );
    }

    // destroy
    public function test_user_can_remove_item_from_cart()
    {
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $cartItem = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product1->id,
        ]);

        $payload = [
            'product_id' => $this->product1->id,
        ];

        $removeCartItem = $this->mock(\App\Actions\CartItem\RemoveCartItem::class);
        $removeCartItem
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, $this->product1->id);

        $response = $this
            ->actingAs($this->user)
            ->from(route('cart.index'))
            ->delete(
                route('cart.items.destroy', $cartItem->id),
                $payload
            );

        $response->assertRedirect(route('cart.index'));

        $response->assertSessionHas(
            'success',
            'Item removed from cart.'
        );
    }
}
