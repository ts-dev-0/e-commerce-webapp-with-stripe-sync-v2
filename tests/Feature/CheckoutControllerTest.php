<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Actions\User\Cart\GetCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_authenticated_user_can_view_checkout_page()
    {
        /** @var \APP\Models\User $user */
        $user = User::factory()->create();

        $product1 = Product::factory()->create([
            'is_published' => 1,
        ]);
        $product2 = Product::factory()->create([
            'is_published' => 1,
        ]);

        $data = [
            [
                'product' => $product1->toArray(),
                'quantity' => 2,
            ],
            [
                'product' => $product2->toArray(),
                'quantity' => 1,
            ]
        ];

        $mock = Mockery::mock(GetCart::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user)
            ->andReturn($data);

        $this->app->instance(GetCart::class, $mock);

        $response = $this
            ->actingAs($user)
            ->get(route('checkout.index'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('checkout')
                 ->where('data', $data)
        );
    }

    public function test_guest_cannot_access_checkout_page()
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect('/login');
    }
}