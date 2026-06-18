<?php

namespace Tests\Feature\Address;

use App\Actions\Address\CreateAddress;
use App\Models\Address;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_creates_the_first_address_as_default()
    {
        $user = User::factory()->create();
        $newAddressData = [
            'full_name' => 'Test User',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '中央区',
            'address_line' => '銀座4-5-6',
            'phone_number' => '09012345678',
            'is_default' => true,
        ];

        app(CreateAddress::class)->handle($user, $newAddressData);

        $this->assertDatabaseCount('addresses', 1);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'is_default' => true,
        ]);
    }

    public function test_it_switches_the_default_address_when_creating_a_new_default_address()
    {
        $user = User::factory()->create();
        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);
        $newAddressData = [
            'full_name' => 'Test User',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '中央区',
            'address_line' => '銀座4-5-6',
            'phone_number' => '09012345678',
            'is_default' => true,
        ];

        app(CreateAddress::class)->handle($user, $newAddressData);

        $this->assertDatabaseCount('addresses', 2);
        $this->assertDatabaseHas('addresses', [
            'id' => $defaultAddress->id,
            'is_default' => false,
        ]);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'full_name' => 'Test User',
            'is_default' => true,
        ]);
    }

    public function test_it_marks_the_first_address_as_default_even_when_is_default_is_false()
    {
        $user = User::factory()->create();
        $newAddressData = [
            'full_name' => 'Test User',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '中央区',
            'address_line' => '銀座4-5-6',
            'phone_number' => '09012345678',
            'is_default' => false,
        ];

        app(CreateAddress::class)->handle($user, $newAddressData);

        $this->assertDatabaseCount('addresses', 1);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'full_name' => 'Test User',
            'is_default' => true,
        ]);
    }

    /**
     *  Exception Cases
     */

    /**
     *  Edge Cases
     */
}
