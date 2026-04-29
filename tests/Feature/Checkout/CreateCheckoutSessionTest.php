<?php

namespace Tests\Feature\Actions\Stripe;

use App\Actions\Stripe\CreateCheckoutSession;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;
use Tests\TestCase;

class CreateCheckoutSessionTest extends TestCase
{
    use RefreshDatabase;

    /** @var \App\Models\User $user */
    private User $user;

    /** @var \App\Models\Product $product */
    private Product $product;

    /** @var \App\Models\Address $address */
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Models\User $user */
        $this->user = User::factory()->create();

        /** @var \App\Models\Product $product */
        $this->product = Product::factory()->create([
            'price' => 1000,
        ]);

        /** @var \App\Models\Address $address */
        $this->address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_it_returns_stripe_session_url(): void
    {
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $cart->products()->attach($this->product->id, [
            'quantity' => 2,
        ]);

        $mockSession = Session::constructFrom([
            'url' => 'https://checkout.stripe.com/test-session',
        ]);

        $action = $this->partialMock(
            CreateCheckoutSession::class,
            function ($mock) use ($mockSession) {
                $mock->shouldAllowMockingProtectedMethods()
                    ->shouldReceive('createSession')
                    ->once()
                    ->andReturn($mockSession);
            }
        );

        $url = $action->handle(
            $this->user,
            $this->address->id
        );

        $this->assertSame(
            'https://checkout.stripe.com/test-session',
            $url
        );
    }

    public function test_it_throws_exception_when_cart_is_empty(): void
    {
        Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $action = new CreateCheckoutSession();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cart is empty.');

        $action->handle(
            $this->user,
            $this->address->id
        );
    }
}
