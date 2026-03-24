<?php

namespace Tests\Feature\Favorite;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Favorite\AddFavorite;
use App\Models\Product;
use App\Models\User;

class AddFavoriteTest extends TestCase
{
    use RefreshDatabase;

    private AddFavorite $action;
    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new AddFavorite();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_user_can_add_product_to_favorites()
    {
        $this->action->handle($this->user, $this->product->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_duplicate_favorite_is_not_created()
    {
        $this->action->handle($this->user, $this->product->id);
        $this->action->handle($this->user, $this->product->id);

        $this->assertDatabaseCount('favorites', 1);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    }
}
