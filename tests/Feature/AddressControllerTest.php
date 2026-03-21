<?php

namespace Tests\Feature;

use App\Actions\User\Address\StoreAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    // store
    public function test_authenticated_user_can_store_address()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $address = [
            'full_name' => 'test name',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '09000000000',
            'is_default' => true,
        ];

        $mock = Mockery::mock(StoreAddress::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user, $address);

        $this->app->instance(StoreAddress::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('addresses.store'), $address);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('success', 'Created Address.');
    }

    public function test_guest_cannot_access_reviews_page()
    {
        $response = $this->post(route('addresses.store'));

        $response->assertRedirect(route('login'));
    }
}
