<?php

namespace Tests\Feature;

use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Traits\MocksActions;
use App\Actions\Home\HomeIndex;
use App\Models\Product;

class HomeControllerTest extends TestCase
{
    use MocksActions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_returns_products_to_home_page()
    {
        $products = collect([
            Product::factory()->make(),
            Product::factory()->make(),
        ]);

        $this->mockAction(
            HomeIndex::class,
            [],
            $products,
        );

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) =>
            $page->component('home')
                 ->where('data', $products->toArray())
        );
    }
}