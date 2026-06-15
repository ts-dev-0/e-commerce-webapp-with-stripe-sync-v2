<?php

namespace Tests\Feature\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchProductsControllerTest extends TestCase
{
    use RefreshDatabase;

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

        $searchPublishedProducts = $this->mock(\App\Actions\Product\SearchProducts::class);
        $searchPublishedProducts
            ->shouldReceive('handle')
            ->with($parameter['keyword'])
            ->andReturn($products);

        $response = $this
            ->actingAs($this->user)
            ->get(route('search.products', $parameter));

        $response->assertOk();
    }

    public function test_guest_cannot_search_products()
    {
        $response = $this->get(route('search.products', [
            'keyword' => 'iphone',
        ]));

        $response->assertRedirect(route('login'));
    }
}
