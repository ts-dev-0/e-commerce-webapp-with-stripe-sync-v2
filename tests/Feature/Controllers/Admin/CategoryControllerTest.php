<?php

namespace Tests\Feature\Controllers\Admin;

use App\Actions\Admin\Category\CreateCategory;
use App\Actions\Admin\Category\DeleteCategory;
use App\Actions\Admin\Category\UpdateCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    // store
    public function test_admin_can_create_category()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $data = [
            'name' => 'Test Category',
        ];

        $mock = Mockery::mock(CreateCategory::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with($data);

        $this->app->instance(CreateCategory::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), $data);
        
        $response->assertRedirect(route('admin.categories.index'));

        $response->assertSessionHas('success');
    }

    // update
    public function test_admin_can_update_category()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'name' => 'Updated Category',
        ];

        $mock = Mockery::mock(UpdateCategory::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with(Mockery::type(Category::class), $data);

        $this->app->instance(UpdateCategory::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('admin.categories.index'))
            ->patch(route('admin.categories.update', $category), $data);
        
        $response->assertRedirect(route('admin.categories.index'));

        $response->assertSessionHas('success');
    }

    // destroy
    public function test_admin_can_delete_category()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
        ];

        $mock = Mockery::mock(DeleteCategory::class);

        $mock->shouldReceive('handle')
            ->once()
            ->with(Mockery::type(Category::class));

        $this->app->instance(DeleteCategory::class, $mock);

        $response = $this
            ->actingAs($user)
            ->from(route('admin.categories.index'))
            ->delete(route('admin.categories.destroy', $category), $data);
        
        $response->assertRedirect(route('admin.categories.index'));

        $response->assertSessionHas('success');
    }
}
