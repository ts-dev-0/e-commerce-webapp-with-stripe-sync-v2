<?php

namespace Tests\Feature\Actions\User\Favorite;

use App\Actions\User\Favorite\AddFavorite;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddFavoriteTest extends TestCase
{
    use RefreshDatabase;

     public function test_user_can_add_product_to_favorites()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $action = new AddFavorite();
        $action->handle($user, $product);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_duplicate_favorite_is_not_created()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $action = new AddFavorite();

        $action->handle($user, $product);
        $action->handle($user, $product);

        $this->assertEquals(1, \DB::table('favorites')->count());
    }
}
