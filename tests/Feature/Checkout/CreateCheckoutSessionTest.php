<?php

namespace Tests\Feature\Actions\Stripe;

use App\Actions\Stripe\CreateCheckoutSession;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\Checkout\Session;
use Tests\TestCase;

class CreateCheckoutSessionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private Address $address;
    private CreateCheckoutSession $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
        $this->address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);
        $this->action = new CreateCheckoutSession();
    }

    public function test_it_returns_stripe_session_url()
    {
        $cart = Cart::factory()->create([
            'user_id' => $this->user->id,
        ]);
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $mockUrl = 'https://checkout.stripe.com/test_url';
        $sessionMock = Mockery::mock('alias:' . Session::class);

        $sessionMock->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($params) {
                $hasCorrectUser = $params['metadata']['user_id'] === $this->user->id;
                $hasCorrectAddress = $params['metadata']['address_id'] === $this->address->id;
                $hasCorrectAmount = $params['line_items'][0]['price_data']['unit_amount'] === $this->product->price;
                $hasCorrectQuantity = $params['line_items'][0]['quantity'] === 1;

                return $hasCorrectUser && $hasCorrectAddress && $hasCorrectAmount && $hasCorrectQuantity;
            }))
            ->andReturn((object) ['url' => $mockUrl]);

        $result = $this->action->handle($this->user, $this->address->id);

        $this->assertEquals($mockUrl, $result);
    }
}
