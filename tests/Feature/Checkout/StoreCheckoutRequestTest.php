<?php

namespace Tests\Feature\Checkout;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreCheckoutRequestTest extends TestCase
{
    use RefreshDatabase;

    private StoreCheckoutRequest $request;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new StoreCheckoutRequest();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_validation_passes_with_valid_data()
    {
        $address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $validator = Validator::make([
            'address_id' => $address->id,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_the_address_id_belongs_to_another_user()
    {
        /** @var \App\Models\User $anotherUser */
        $anotherUser = User::factory()->create();
        $this->actingAs($anotherUser);

        $address = Address::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $validator = Validator::make([
            'address_id' => $address->id,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_address_does_not_exists()
    {
        $validator = Validator::make([
            'address_id' => 9999,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
