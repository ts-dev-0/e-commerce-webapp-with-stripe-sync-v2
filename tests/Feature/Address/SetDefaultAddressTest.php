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

    private SetDefaultAddress $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new SetDefaultAddress();
        $this->user = User::factory()->create();
    }

    public function test_action_sets_given_address_as_default()
    {
        /** @var \App\Models\Address $defaultAddress */
        $defaultAddress = Address::factory()->create([
            'is_default' => true,
            'user_id' => $this->user->id,
        ]);

        /** @var \App\Models\Address $newDefaultAddress */
        $newDefaultAddress = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->action->handle($this->user, $newDefaultAddress);

        $this->assertDatabaseHas('addresses', [
            'id' => $defaultAddress->id,
            'is_default' => false,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $newDefaultAddress->id,
            'is_default' => true,
        ]);
    }

    public function test_action_does_not_update_when_address_is_already_default()
    {
        /** @var \App\Models\Address $defaultAddress */
        $defaultAddress = Address::factory()->create([
            'is_default' => true,
            'user_id' => $this->user->id,
        ]);

        $originalUpdatedAt = $defaultAddress->updated_at;

        $this->action->handle($this->user, $defaultAddress);

        $defaultAddress->refresh();

        $this->assertEquals(
            $originalUpdatedAt,
            $defaultAddress->updated_at
        );
    }
}
