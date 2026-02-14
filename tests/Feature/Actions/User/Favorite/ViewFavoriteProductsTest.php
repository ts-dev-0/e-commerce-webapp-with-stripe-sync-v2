<?php

namespace Tests\Feature\Actions\User\Favorite;

use App\Actions\User\Favorite\ViewFavoriteProducts;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewFavoriteProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_only_their_favorite_products()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $otherProduct = Product::factory()->create();

        $user->favoriteProducts()->attach($product1->id);
        $user->favoriteProducts()->attach($product2->id);
        $otherUser->favoriteProducts()->attach($otherProduct->id);

        $action = new ViewFavoriteProducts();

        $products = $action->handle($user);

        $this->assertCount(2, $products);

        $this->assertTrue($products->pluck('id')->contains($product1->id));
        $this->assertTrue($products->pluck('id')->contains($product2->id));
        $this->assertFalse($products->pluck('id')->contains($otherProduct->id));
    }

    public function test_favorites_are_returned_in_descending_created_at_order()
    {
        $user = User::factory()->create();

        $oldProduct = Product::factory()->create();
        $newProduct = Product::factory()->create();

        $user->favoriteProducts()->attach($oldProduct->id, [
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $user->favoriteProducts()->attach($newProduct->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $action = new ViewFavoriteProducts();

        $products = $action->handle($user)->values();

        $this->assertEquals($newProduct->id, $products[0]->id);
        $this->assertEquals($oldProduct->id, $products[1]->id);
    }

}
