<?php

namespace Tests\Feature\Favorite;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\User\Favorite\RemoveFavorite;
use App\Models\Product;
use App\Models\User;

class RemoveFavoriteTest extends TestCase
{
    use RefreshDatabase;

    private RemoveFavorite $action;
    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new RemoveFavorite();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_user_can_remove_product_from_favorites()
    {
        $this->user->favoriteProducts()->attach($this->product->id);

        $this->action->handle($this->user, $this->product->id);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }
}
