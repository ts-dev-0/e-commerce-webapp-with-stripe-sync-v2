<?php

namespace Tests\Feature\Category;

use App\Actions\Admin\Category\CreateCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    private CreateCategory $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateCategory();
    }

    public function test_admin_can_create_category()
    {
        $data = [
            'name' => 'Electronics',
        ];

        $this->assertDatabaseMissing('categories', [
            'name' => 'Electronics',
        ]);

        $category = $this->action->handle($data);

        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
        ]);

        $this->assertEquals('Electronics', $category->name);
    }
}
