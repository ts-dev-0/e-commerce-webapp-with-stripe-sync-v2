<?php

namespace Tests\Feature\Actions\Admin\Category;

use App\Actions\Admin\Category\DeleteCategory;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    private DeleteCategory $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteCategory();
    }

    public function test_admin_can_delete_category()
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);

        $this->action->handle($category);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
