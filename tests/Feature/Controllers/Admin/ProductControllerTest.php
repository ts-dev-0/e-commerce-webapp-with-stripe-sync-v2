<?php

namespace Tests\Feature\Controllers\Admin;

use App\Actions\Admin\Product\CreateProduct;
use App\Actions\Admin\Product\DeleteProduct;
use App\Actions\Admin\Product\UpdateProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // store
    public function test_admin_can_create_new_product()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $validatedData = [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'New manufacturer',
        ];

        $mock = Mockery::mock(CreateProduct::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($validatedData);

        $this->app->instance(CreateProduct::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), $validatedData);

        $response->assertRedirect(route('admin.products.create'));

        $response->assertSessionHas('success');
    }

    // update
    public function test_admin_can_update_product()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $product = Product::factory()->create();

        $data = [
            'name' => 'Updated test Product',
            'description' => 'Test description',
            'price' => 1000,
            'stock' => 10,
            'manufacturer' => 'New manufacturer',
        ];

        $mock = Mockery::mock(UpdateProduct::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with(Mockery::type(Product::class), $data)
            ->andReturn($product);

        $this->app->instance(UpdateProduct::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('admin.products.edit', $product))
            ->patch(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.edit', $product));

        $response->assertSessionHas('success');
    }

    // destroy
    public function test_admin_can_delete_product()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $product = Product::factory()->create();

        $data = [
            'product_id' => $product->id,
        ];

        $mock = Mockery::mock(DeleteProduct::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with(Mockery::type(Product::class));

        $this->app->instance(DeleteProduct::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('admin.products.edit', $product->id))
            ->delete(route('admin.products.destroy', $product->id), $data);

        $response->assertRedirect(route('admin.products.create'));

        $response->assertSessionHas('success');
    }
}
