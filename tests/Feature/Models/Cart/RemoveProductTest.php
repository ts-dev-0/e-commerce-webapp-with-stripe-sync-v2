<?php

namespace Tests\Feature\Models\Cart;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemoveProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_can_remove_product()
    {
        /** @var \App\Models\User $user  */
        $user = User::factory()->create();

        $cart = $user->currentCart();

        $product = Product::factory()->create();

        $cart->addProduct($product);

        $cart->removeProduct($product);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }
}
