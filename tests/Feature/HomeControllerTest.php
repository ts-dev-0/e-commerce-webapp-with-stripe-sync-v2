<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_index_returns_products_to_home_page()
    {
        Product::factory()->count(20)->create([
            'is_published' => true,
        ]);

        Product::factory()->create([
            'is_published' => false,
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();

        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('home')
                ->has('products', 15)
        );
    }
}
