<?php

namespace Tests\Feature\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetDefaultAddressRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_user_can_update_default_address()
    {
        /** @var \App\Models\Address $previousDefaultAddress */
        $previousDefaultAddress = Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => true,
        ]);

        /** @var \App\Models\Address $newDefaultAddress */
        $newDefaultAddress = Address::factory()->create([
            'user_id' => $this->user->id,
            'is_default' => false,
        ]);

        $response = $this->actingAs($this->user)->patch(
            route('addresses.default.update', $newDefaultAddress)
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('addresses', [
            'id' => $previousDefaultAddress->id,
            'is_default' => false,
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $newDefaultAddress->id,
            'is_default' => true,
        ]);
    }

    public function test_user_cannot_update_other_users_default_address()
    {
        /** @var \App\Models\User $otherUser */
        $otherUser = User::factory()->create();

        /** @var \App\Models\Address $otherUsersAddress */
        $otherUsersAddress = Address::factory()->create([
            'user_id' => $otherUser->id,
            'is_default' => false,
        ]);

        $response = $this->actingAs($this->user)->patch(
            route('addresses.default.update', $otherUsersAddress)
        );

        $response->assertForbidden();

        $this->assertDatabaseHas('addresses', [
            'id' => $otherUsersAddress->id,
            'is_default' => false,
        ]);
    }
}
