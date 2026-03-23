<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\MocksActions;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\User\Search\SearchPublishedProduct;
use App\Models\Product;
use App\Models\User;

class SearchPublishedProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use MocksActions;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = User::factory()->create();
    }

    public function test_user_can_search_products()
    {
        $parameter = ['keyword' => 'iphone'];

        $products = Collection::make([
            Product::factory()->make(),
            Product::factory()->make(),
        ]);

        $this->mockAction(
            SearchPublishedProduct::class,
            [$parameter['keyword']],
            $products,
        );

        $response = $this
            ->actingAs($this->user)
            ->get(route('search.products', $parameter));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('search-product')
                ->where('data', $products->toArray())
        );
    }

    public function test_guest_cannot_search_products()
  {
      $response = $this->get(route('search.products', [
          'keyword' => 'iphone',
      ]));

      $response->assertRedirect(route('login'));
  }
}
