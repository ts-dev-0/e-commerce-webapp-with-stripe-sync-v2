<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::factory()->create();
    }

    // store
    public function test_authenticated_user_can_store_address()
    {
        $address = [
            'full_name' => 'test name',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '09000000000',
            'is_default' => true,
        ];

        $storeAddress = $this->mock(\App\Actions\Address\CreateAddress::class);
        $storeAddress
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, $address);

        $response = $this
            ->actingAs($this->user)
            ->from(route('checkout.index'))
            ->post(route('addresses.store'), $address);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('success', 'Created Address.');
    }

    // update
    public function test_authenticated_user_can_update_address()
    {
        $existingAddress = Address::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'full_name' => 'updated name',
            'postal_code' => '7654321',
            'prefecture' => '大阪府',
            'city' => '大阪市',
            'address_line' => '4-5-6-202',
            'phone_number' => '08011111111',
            'is_default' => false,
        ];

        $updateAddress = $this->mock(\App\Actions\Address\UpdateAddress::class);
        $updateAddress
            ->shouldReceive('handle')
            ->once()
            ->with($this->user, Mockery::type(Address::class), $updatedData);

        $response = $this
            ->actingAs($this->user)
            ->from(route('account.addresses'))
            ->patch(route('addresses.update', $existingAddress), $updatedData);

        $response->assertRedirect(route('account.addresses'));
        $response->assertSessionHas('success', 'Updated Address.');
    }

    public function test_authenticated_user_can_delete_address()
    {
        $existingAddress = Address::factory()->create(['user_id' => $this->user->id]);

        $deleteAddress = $this->mock(\App\Actions\Address\DeleteAddress::class);
        $deleteAddress
            ->shouldReceive('handle')
            ->once()
            ->with(Mockery::type(Address::class));

        $response = $this
            ->actingAs($this->user)
            ->from(route('account.addresses'))
            ->delete(route('addresses.destroy', $existingAddress));

        $response->assertRedirect(route('account.addresses'));
        $response->assertSessionHas('success', 'Deleted Address.');
    }

    public function test_guest_cannot_access_reviews_page()
    {
        $response = $this->post(route('addresses.store'));

        $response->assertRedirect(route('login'));
    }
}
