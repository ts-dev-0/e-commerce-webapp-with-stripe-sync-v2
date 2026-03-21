<?php

namespace Tests\Feature\Actions\User\Address;

use App\Actions\User\Address\StoreAddress;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreAddressTest extends TestCase
{
    use RefreshDatabase;

    private StoreAddress $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new StoreAddress();
    }

    public function test_user_can_store_new_address()
    {
        $user = User::factory()->create();

        $validatedAddressData = [
            'full_name' => 'test name',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '09000000000',
            'is_default' => true,
        ];

        $result = $this->action->handle($user, $validatedAddressData);

        $this->assertDatabaseHas('addresses', [
            'id' => $result->id,
        ]);
    }

    public function test_existing_default_is_unset_when_new_default_is_set()
    {
        $user = User::factory()->create();

        $registerdDefaultAddress = Address::factory()->create([
            'is_default' => true,
            'user_id' => $user->id,
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

        $result = $this->action->handle($user, $validatedAddressData);

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
