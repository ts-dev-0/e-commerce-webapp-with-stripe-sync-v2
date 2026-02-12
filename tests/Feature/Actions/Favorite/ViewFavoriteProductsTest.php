<?php

namespace Tests\Feature\tests\Feature\Actions\Favorite;

use App\Actions\Favorite\ViewFavoriteProducts;
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
}
