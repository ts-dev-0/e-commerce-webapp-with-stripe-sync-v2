<?php

namespace Tests\Feature\Models\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_one_cart_relation()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            $user->cart()
        );
    }
}
