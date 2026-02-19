<?php

namespace Tests\Feature\Actions\Admin\Category;

use App\Actions\Admin\Category\UpdateCategory;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    private UpdateCategory $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateCategory();
    }

    public function test_admin_can_update_category()
    {
        $category = Category::factory()->create([
            'name' => 'Old Name',
        ]);

        $updated = $this->action->handle($category, [
            'name' => 'New Name',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
        ]);

        $this->assertEquals('New Name', $updated->name);
    }
}
