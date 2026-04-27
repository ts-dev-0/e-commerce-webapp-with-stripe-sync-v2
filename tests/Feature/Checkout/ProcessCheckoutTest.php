<?php

namespace Tests\Feature\Actions\Checkout;

use App\Actions\Checkout\ProcessCheckout;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;
use Tests\TestCase;

class ProcessCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_order_and_order_items_and_updates_stock_and_clears_cart(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        /** @var \App\Models\Address $address */
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        /** @var \App\Models\Cart $cart */
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);

        /** @var \App\Models\Product $product */
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 3000,
            'stock' => 20,
        ]);

        $cart->products()->attach($product->id, [
            'quantity' => 2,
        ]);

        $session = Session::constructFrom([
            'metadata' => [
                'address_id' => $address->id,
            ],
            'amount_total' => 6000,
        ]);
        $session->amount_total = 6000;

        $action = new ProcessCheckout();

        $order = $action->handle($user, $session);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'total_amount' => 6000,
            'full_name' => $address->full_name,
            'postal_code' => $address->postal_code,
            'prefecture' => $address->prefecture,
            'city' => $address->city,
            'address_line' => $address->address_line,
            'phone_number' => $address->phone_number,
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'price' => 3000,
            'subtotal' => 6000,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 18,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);
    }
}
