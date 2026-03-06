<?php

namespace Tests\Feature\Actions\User\Favorite;

use App\Actions\User\Favorite\RemoveFavorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RemoveFavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_remove_product_from_favorites()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->favoriteProducts()->attach($product->id);

        $action = new RemoveFavorite();
        $action->handle($user, $product->id);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
