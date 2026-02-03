<?php

namespace Tests\Feature\Models\Cart;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateProductQuantityTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_can_update_product_quantity()
    {
        /** @var \App\Models\User $user  */
        $user = User::factory()->create();

        $cart = $user->currentCart();

        $product = Product::factory()->create();

        $cart->addProduct($product);

        $cart->refresh();

        $updatedQuantity = 3;

        $cart->updateProductQuantity($product, $updatedQuantity);

        $this->assertDatabaseHas('cart_items', [
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'quantity'   => $updatedQuantity,
            ]
        );
    }

    public function test_throws_exception_if_product_not_in_cart()
    {
        /** @var \App\Models\User $user  */
        $user = User::factory()->create();

        $cart = $user->currentCart();

        $product = Product::factory()->create();

        $this->expectException(\InvalidArgumentException::class);

        $cart->updateProductQuantity($product, 3);
    }

    public function test_throws_exception_if_quantity_is_zero_or_less()
    {
        /** @var \App\Models\User $user  */
        $user = User::factory()->create();

        $cart = $user->currentCart();

        $product = Product::factory()->create();

        $cart->addProduct($product);

        $cart->refresh();

        $updatedQuantity = 0;

        $this->expectException(\InvalidArgumentException::class);

        $cart->updateProductQuantity($product, $updatedQuantity);
    }
}
