<?php

namespace Tests\Feature;

use App\Models\User;
use App\Actions\User\Search\SearchPublishedProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class SearchPublishedProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_user_can_search_products()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $keyword = 'iphone';

        $products = Collection::make([
            Product::factory()->make(),
            Product::factory()->make(),
        ]);

        $mock = Mockery::mock(SearchPublishedProduct::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($keyword)
            ->andReturn($products);

        $this->app->instance(SearchPublishedProduct::class, $mock);

        $response = $this
            ->actingAs($user)
            ->get(route('search.products', [
                'keyword' => $keyword,
            ]));

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
