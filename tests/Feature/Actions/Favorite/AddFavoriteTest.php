<?php

namespace Tests\Feature\Actions\Favorite;

use App\Actions\Favorite\AddFavorite;
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
}
