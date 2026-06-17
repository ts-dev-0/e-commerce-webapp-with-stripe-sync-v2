<?php

namespace Tests\Feature\Address;

use App\Actions\Address\SetDefaultAddress;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetDefaultAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Happy Path
     */
    public function test_it_switches_the_default_address()
    {
        $user = User::factory()->create();
        $oldDefaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);
        $newDefaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        app(SetDefaultAddress::class)->handle($user, $newDefaultAddress);

        $this->assertDatabaseHas('addresses', [
            'id' => $oldDefaultAddress->id,
            'user_id' => $user->id,
            'is_default' => false,
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $newDefaultAddress->id,
            'user_id' => $user->id,
            'is_default' => true,
        ]);
    }

    /**
     *  Exception Cases
     */

    /**
     *  Edge Cases
     */
    public function test_it_keeps_the_address_as_default_when_it_is_already_the_default_address()
    {
        $user = User::factory()->create();
        $defaultAddress = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => true,
        ]);


        app(SetDefaultAddress::class)->handle($user, $defaultAddress);

        $this->assertDatabaseHas('addresses', [
            'id' => $defaultAddress->id,
            'user_id' => $user->id,
            'is_default' => true,
        ]);
    }
}
