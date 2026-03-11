<?php

namespace Tests\Feature\Controllers\Admin;

use App\Actions\Admin\Search\SearchAllProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class SearchAllProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_validation_passes_with_valid_data()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $products = collect([
            Product::factory()->make(),
            Product::factory()->make(),
        ]);

        $keyword = 'iphone';

        $mock = Mockery::mock(SearchAllProduct::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($keyword)
            ->andReturn($products);

        $this->app->instance(SearchAllProduct::class, $mock);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.search.products', [
                'keyword' => $keyword,
            ]));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('admin/search/all-products')
                ->where('data', $products->toArray())
        );
    }
}
