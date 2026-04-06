<?php

namespace Tests\Feature\Address;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Address\UpdateAddress;
use App\Models\Address;
use App\Models\User;

class UpdateAddressTest extends TestCase
{
    use RefreshDatabase;

    private UpdateAddress $action;
    private User $user;
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new UpdateAddress();
        $this->user = User::factory()->create();
        $this->address = Address::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_user_can_update_address()
    {
        $updatedData = [
            'full_name' => 'updated name',
            'postal_code' => '7654321',
            'prefecture' => '大阪府',
            'city' => '大阪市',
            'address_line' => '4-5-6-202',
            'phone_number' => '08011111111',
            'is_default' => false,
        ];

        $result = $this->action->handle($this->user, $this->address, $updatedData);

        $this->assertEquals('updated name', $result->full_name);
        $this->assertEquals('7654321', $result->postal_code);
        $this->assertEquals('大阪府', $result->prefecture);
        $this->assertEquals('大阪市', $result->city);
        $this->assertEquals('4-5-6-202', $result->address_line);
        $this->assertEquals('08011111111', $result->phone_number);
        $this->assertFalse($result->is_default);
    }

    public function test_existing_default_is_unset_when_new_default_is_set()
    {
        $existingDefaultAddress = Address::factory()->create([
            'is_default' => true,
            'user_id' => $this->user->id,
        ]);

        $updatedData = [
            'full_name' => 'updated name',
            'postal_code' => '7654321',
            'prefecture' => '大阪府',
            'city' => '大阪市',
            'address_line' => '4-5-6-202',
            'phone_number' => '08011111111',
            'is_default' => true,
        ];

        $this->action->handle($this->user, $this->address, $updatedData);

        $this->assertFalse($existingDefaultAddress->fresh()->is_default);
        $this->assertTrue($this->address->fresh()->is_default);
    }
}