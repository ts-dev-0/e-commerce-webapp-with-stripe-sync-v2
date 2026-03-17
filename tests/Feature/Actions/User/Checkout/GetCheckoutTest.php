<?php

namespace Tests\Feature\Actions\User\Cart;

use App\Actions\User\Checkout\GetCheckout;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private GetCheckout $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetCheckout();
    }

    public function test_user_can_get_checkout_data()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        /** @var \App\Models\User $otherUser */
        $otherUser = User::factory()->create();

        $product1 = Product::factory()->create([
            'price' => 100,
        ]);
        $product2 = Product::factory()->create([
            'price' => 200,
        ]);

        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherCart = Cart::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product1->id,
            'quantity' => 2,
        ]);

        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product2->id,
            'quantity' => 5,
        ]);

        CartItem::factory()->create([
            'cart_id' => $otherCart->id,
            'product_id' => Product::factory()->create()->id,
            'quantity' => 10,
        ]);

        $result = $this->action->handle($user);

        $this->assertCount(2, $result['items']);

        $this->assertEqualsCanonicalizing(
            [2, 5],
            array_column($result['items'], 'quantity')
        );
        // subtotal = (100 * 2) + (200 * 5) = 1200
        $this->assertEquals(1200, $result['subtotal']);
    }

    public function test_empty_checout_data_returns_empty_array()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $result = $this->action->handle($user);

        $this->assertEmpty($result['items']);
        $this->assertEquals(0, $result['subtotal']);
    }
}
