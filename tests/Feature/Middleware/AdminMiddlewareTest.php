<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_admin_user_can_access_admin_route()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.products.create'));

        $response->assertOk();
    }

    public function test_non_admin_user_cannot_access_admin_route()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.products.create'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_admin_route()
    {
        $response = $this->get(route('admin.products.create'));

        $response->assertRedirect(route('login'));
    }
}
