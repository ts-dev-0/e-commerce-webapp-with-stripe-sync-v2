<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Cart\GetCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

class GetCartTest extends TestCase
{
    use RefreshDatabase;

    private GetCart $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetCart();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_cart_data()
    {
        $product = Product::factory()->create(['price' => 100]);

        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $result = $this->action->handle($this->user);

        $this->assertCount(1, $result->items);
        $this->assertEquals(200, $result->subtotal);
    }

    public function test_empty_cart_data_returns_empty_structure()
    {
        $result = $this->action->handle($this->user);

        $this->assertInstanceOf(\App\DTOs\CartData::class, $result);
        $this->assertTrue($result->items->isEmpty());
        $this->assertEquals(0, $result->subtotal);
    }
}
