<?php

namespace Tests\Feature\Requests\Admin\Category;

use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateCategoryRequestTest extends TestCase
{
    use RefreshDatabase;

    private UpdateCategoryRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new UpdateCategoryRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $category = Category::factory()->create();

        $validator = Validator::make([
            'category_id' => $category->id,
            'name' => 'Updated test category',
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_category_id_field_is_not_exists()
    {
        $category = Category::factory()->create();

        $validator = Validator::make([
            'name' => 'Updated test category',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_category_id_is_string()
    {
        Category::factory()->create();

        $validator = Validator::make([
            'category_id' => 'categoryId',
            'name' => 'Updated test category',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_category_is_not_exists()
    {
        Category::factory()->create();

        $validator = Validator::make([
            'category_id' => 1000,
            'name' => 'Updated test category',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_field_is_not_exists()
    {
        $category = Category::factory()->create();

        $validator = Validator::make([
            'category_id' => $category->id,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_integer()
    {
        $category = Category::factory()->create();

        $validator = Validator::make([
            'category_id' => $category->id,
            'name' => 100,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_more_than_255()
    {
        $category = Category::factory()->create();

        $validator = Validator::make([
            'category_id' => $category->id,
            'name' => str_repeat('a', 256),
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
