<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\MocksActions;
use App\Actions\User\Favorite\AddFavorite;
use App\Actions\User\Favorite\RemoveFavorite;
use App\Actions\User\Favorite\ViewFavoriteProducts;
use App\Models\User;
use App\Models\Product;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    // index
    public function test_authenticated_user_can_view_favorite_products()
    {
        $favorites = new Collection($this->product);

        $this->mockAction(
            ViewFavoriteProducts::class,
            [$this->user],
            $favorites
        );

        $response = $this
            ->actingAs($this->user)
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
        $this->mockAction(
            AddFavorite::class,
            [$this->user, $this->product->id],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('favorites.index'))
            ->post(route('favorites.store'), [
                'product_id' => $this->product->id,
            ]);

        $response->assertRedirect(route('favorites.index'));

        $response->assertSessionHas('success', 'Added to favorite.');
    }

    public function test_guest_cannot_add_favorite()
    {
        $response = $this->post(route('favorites.store'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect(route('login'));
    }

    // destroy
    public function test_user_can_remove_favorite_product()
    {
        $this->mockAction(
            RemoveFavorite::class,
            [$this->user, $this->product->id],
        );

        $response = $this
            ->actingAs($this->user)
            ->from(route('favorites.index'))
            ->delete(route('favorites.destroy'), [
                'product_id' => $this->product->id,
            ]);

        $response->assertRedirect(route('favorites.index'));

        $response->assertSessionHas('success', 'Removed favorite.');
    }

    public function test_guest_cannot_remove_favorite_product()
    {
        $response = $this->delete(route('favorites.destroy'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect(route('login'));
    }
}