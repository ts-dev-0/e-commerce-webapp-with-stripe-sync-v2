<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Product;
use App\Actions\User\Favorite\AddFavorite;
use App\Actions\User\Favorite\ViewFavoriteProducts;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // index
    public function test_authenticated_user_can_view_favorite_products()
    {
        /** @var \APP\Models\User $user */
        $user = User::factory()->create();
        $product1 = Product::factory()->create();

        $favorites = new Collection($product1);

        $mock = Mockery::mock(ViewFavoriteProducts::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user)
            ->andReturn($favorites);

        $this->app->instance(ViewFavoriteProducts::class, $mock);

        $response = $this
            ->actingAs($user)
            ->get(route('favorites.index'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('favorite-products')
                 ->where('data', $favorites)
        );
    }

    public function test_guest_cannot_access_favorites_page()
    {
        $response = $this->get(route('favorites.index'));

        $response->assertRedirect(route('login'));
    }

    // store
    public function test_user_can_add_product_to_favorite()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $mock = Mockery::mock(AddFavorite::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($user, $product->id);

        $this->app->instance(AddFavorite::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('favorites.index'))
            ->post(route('favorites.store'), [
                'product_id' => $product->id,
            ]);

        $response->assertRedirect(route('favorites.index'));

        $response->assertSessionHas('success', 'Added to favorite.');
    }

    public function test_guest_cannot_add_favorite()
    {
        $product = Product::factory()->create();

        $response = $this->post(route('favorites.store'), [
            'product_id' => $product->id,
        ]);

        $response->assertRedirect('/login');
    }
}