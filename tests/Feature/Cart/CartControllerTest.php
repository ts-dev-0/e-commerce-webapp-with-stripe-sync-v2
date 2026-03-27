<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\MocksActions;
use App\Actions\Cart\AddItemToCart;
use App\Actions\Cart\GetCart;
use App\Actions\Cart\RemoveCartItem;
use App\Actions\Cart\UpdateCartItemQuantity;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\Product;
use App\DTOs\CartData;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

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

    // index
    public function test_authenticated_user_can_view_cart()
    {
        $items = collect([
            new CartItem([
                'product' => $this->product1,
                'quantity' => 2
            ]),
            new CartItem([
                'product' => $this->product2,
                'quantity' => 1
            ]),
        ]);

        $cartData = new CartData(
            items: $items,
            subtotal: 400
        );

        $this->mockAction(
            GetCart::class,
            [$this->user],
            $cartData
        );

        $response = $this
            ->actingAs($this->user)
            ->get(route('cart.index'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_cart()
    {
        $response = $this->get(route('cart.index'));

        $response->assertRedirect(route('login'));
    }

    // store
    public function test_user_can_add_item_to_cart()
    {
        $payload = [
            'product_id' => $this->product1->id,
            'quantity' => 2,
        ];

        $this->mockAction(
            AddItemToCart::class,
            [$this->user, $this->product1->id, 2]
        );

        $response = $this
            ->actingAs($this->user)
            ->post(route('cart.store'), $payload);

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
            ->post(route('cart.store'), [
                'product_id' => $this->product1->id,
            ]);

        $response->assertSessionHasErrors('quantity');
    }

    // update
    public function test_user_can_update_cart_item_quantity()
    {
        Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $payload = [
            'product_id' => $this->product1->id,
            'quantity' => 3,
        ];

        $this->mockAction(
            UpdateCartItemQuantity::class,
            [$this->user, $this->product1->id, 3]
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('cart.index'))
            ->patch(
                route('cart.items.update'),
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
        $payload = [
            'product_id' => $this->product1->id,
        ];

        $this->mockAction(
            RemoveCartItem::class,
            [$this->user, $this->product1->id]
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('cart.index'))
            ->delete(
                route('cart.items.destroy'),
                $payload
            );

        $response->assertRedirect(route('cart.index'));

        $response->assertSessionHas(
            'success',
            'Item removed from cart.'
        );
    }
}