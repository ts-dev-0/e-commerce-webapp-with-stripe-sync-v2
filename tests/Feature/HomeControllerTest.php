<?php

namespace Tests\Feature;

use Tests\TestCase;
use Mockery;
use App\Models\Product;
use App\Actions\User\Home\HomeIndex;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Testing\AssertableInertia as Assert;

class HomeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_returns_products_to_home_page()
    {
        $products = new Collection([
            Product::factory()->make([
                'id' => 1,
                'name' => 'Product A',
            ]),
            Product::factory()->make([
                'id' => 2,
                'name' => 'Product B',
            ]),
        ]);

        $mock = Mockery::mock(HomeIndex::class);
        $mock->shouldReceive('handle')
            ->once()
            ->andReturn($products);

        $this->app->instance(HomeIndex::class, $mock);

        $response = $this->get('/');

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('home')
                 ->where('data', $products->toArray())
        );
    }
}