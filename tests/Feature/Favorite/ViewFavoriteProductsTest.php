<?php

namespace Tests\Feature\Favorite;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Favorite\ViewFavoriteProducts;
use App\Models\Product;
use App\Models\User;

class ViewFavoriteProductsTest extends TestCase
{
    use RefreshDatabase;

    private ViewFavoriteProducts $action;
    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ViewFavoriteProducts();
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create();
    }

    public function test_user_can_view_only_their_favorite_products()
    {
        $otherUser = User::factory()->create();

        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $otherProduct = Product::factory()->create();

        $this->user->favoriteProducts()->attach($product1->id);
        $this->user->favoriteProducts()->attach($product2->id);
        $otherUser->favoriteProducts()->attach($otherProduct->id);

        $products = $this->action->handle($this->user);

        $this->assertCount(2, $products);

        $this->assertTrue($products->pluck('id')->contains($product1->id));
        $this->assertTrue($products->pluck('id')->contains($product2->id));
        $this->assertFalse($products->pluck('id')->contains($otherProduct->id));
    }

    public function test_favorites_are_returned_in_descending_created_at_order()
    {
        $oldProduct = Product::factory()->create();
        $newProduct = Product::factory()->create();

        $this->user->favoriteProducts()->attach($oldProduct->id, [
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $this->user->favoriteProducts()->attach($newProduct->id, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $products = $this->action->handle($this->user)->values();

        $this->assertEquals($newProduct->id, $products[0]->id);
        $this->assertEquals($oldProduct->id, $products[1]->id);
    }

}
