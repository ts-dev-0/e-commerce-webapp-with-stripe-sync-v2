<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\User\Address\StoreAddress;
use App\Models\User;

use Tests\Traits\MocksActions;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

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

        $this->mockAction(
            StoreAddress::class,
            [$this->user, $address],
        );

        $response = $this
            ->actingAs($this->user)
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
