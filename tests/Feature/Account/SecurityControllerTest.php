<?php

namespace Tests\Feature\Account;

use Inertia\Testing\AssertableInertia as Assert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\MocksActions;

class SecurityControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
    }

    public function test_user_can_view_login_and_security_page()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('account.security'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page
                ->component('account/login-security')
                ->where('name', $this->user->name)
                ->where('email', $this->user->email)
        );
    }

    public function test_guest_cannot_view_login_and_security_page()
    {
        $response = $this->get(route('account.security'));

        $response->assertRedirect(route('login'));
    }


    public function test_login_and_security_page_returns_correct_component()
    {
        $this
            ->actingAs($this->user)
            ->get(route('account.security'))
            ->assertInertia(fn (Assert $page) =>
                $page->component('account/login-security')
            );
    }

    public function test_password_is_not_exposed_in_security_page_props()
    {
        $this
            ->actingAs($this->user)
            ->get(route('account.security'))
            ->assertInertia(fn (Assert $page) =>
                $page->missing('password')
            );
    }
}
