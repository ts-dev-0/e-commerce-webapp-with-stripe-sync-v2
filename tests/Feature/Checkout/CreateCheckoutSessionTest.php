<?php

namespace Tests\Feature\Actions\Stripe;

use App\Actions\Stripe\CreateCheckoutSession;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCheckoutSessionTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_returns_a_checkout_session_url(): void
    {
        $user = User::factory()->create();

        $cart = Cart::factory()->create(['user_id' => $user->id]);

        CartItem::factory()->create(['cart_id' => $cart->id]);

        $address = Address::factory()->create(['user_id' => $user->id]);

        $session = new \Stripe\Checkout\Session();
        $session->url = 'https://checkout.stripe.com/test-session';

        $stripeSessionService = $this->mock(
            \App\Services\StripeSessionService::class
        );

        $stripeSessionService
            ->shouldReceive('createCheckoutSession')
            ->once()
            ->with(
                \Mockery::type(
                    \Illuminate\Database\Eloquent\Collection::class
                ),
                $user,
                $address->id,
            )
            ->andReturn($session);

        $result = app(CreateCheckoutSession::class)->handle(
            user: $user,
            selectedAddressId: $address->id,
        );

        $this->assertEquals(
            'https://checkout.stripe.com/test-session',
            $result
        );
    }

    /**
     *  Exception Cases
     */

    /**
     *  Edge Cases
     */
}
