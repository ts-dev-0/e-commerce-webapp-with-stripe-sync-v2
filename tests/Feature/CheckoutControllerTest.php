<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Product;
use App\Actions\User\Checkout\GetCheckout;
use App\Actions\User\Checkout\ProcessCheckout;
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

    // index
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
            'items' => [
                [
                    'product' => $product1->toArray(),
                    'quantity' => 2,
                ],
                [
                    'product' => $product2->toArray(),
                    'quantity' => 1,
                ],
            ],
            'subtotal' => 0,
        ];

        $mock = Mockery::mock(GetCheckout::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user)
            ->andReturn($data);

        $this->app->instance(GetCheckout::class, $mock);

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

    // store
    public function test_user_can_process_checkout()
    {
        /** @var \APP\Models\User $user */
        $user = User::factory()->create();

        $mock = Mockery::mock(ProcessCheckout::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user);

        $this->app->instance(ProcessCheckout::class, $mock);

        $response = $this
            ->actingAs($user)
            ->post(route('checkout.store'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_process_checkout()
    {
        $response = $this->post(route('checkout.store'));

        $response->assertRedirect(route('login'));
    }
}