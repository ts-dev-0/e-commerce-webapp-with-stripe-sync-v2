<?php

namespace Tests\Feature\Address;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Address\StoreAddress;
use App\Models\Address;
use App\Models\User;

class StoreAddressTest extends TestCase
{
    use RefreshDatabase;

    private StoreAddress $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new StoreAddress();
        $this->user = User::factory()->create();
    }

    public function test_user_can_store_new_address()
    {
        $validatedAddressData = [
            'full_name' => 'test name',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '09000000000',
            'is_default' => true,
        ];

        $result = $this->action->handle($this->user, $validatedAddressData);

        $this->assertDatabaseHas('addresses', [
            'id' => $result->id,
        ]);
    }

    public function test_existing_default_is_unset_when_new_default_is_set()
    {
        $registerdDefaultAddress = Address::factory()->create([
            'is_default' => true,
            'user_id' => $this->user->id,
        ]);

        $validatedAddressData = [
            'full_name' => 'test name',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '09000000000',
            'is_default' => true,
        ];

        $result = $this->action->handle($this->user, $validatedAddressData);

        $this->assertDatabaseHas('addresses', [
            'id' => $registerdDefaultAddress->id,
            'is_default' => false,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $result->id,
            'is_default' => true,
        ]);
    }
}
