<?php

namespace Tests\Feature\Actions\Checkout;

use App\Actions\Checkout\ProcessCheckout;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;
use Tests\TestCase;

class ProcessCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_processes_checkout_successfully()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 1000,
        ]);
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
        ]);
        $cartItems = CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $sessionMock = Session::constructFrom([
            'metadata' => [
                'user_id' => $user->id,
                'address_id' => $address->id,
            ],
            'amount_total' => 2000,
        ]);
        $stripeSessionService = $this->mock(\App\Services\StripeSessionService::class);

        $sessionId = 'test-sessionId';
        $stripeSessionService
            ->shouldReceive('retrieveStripeSession')
            ->once()
            ->with($sessionId)
            ->andReturn($sessionMock);

        app(ProcessCheckout::class)->handle($user, $sessionId);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseCount('cart_items', 0);
    }
}
