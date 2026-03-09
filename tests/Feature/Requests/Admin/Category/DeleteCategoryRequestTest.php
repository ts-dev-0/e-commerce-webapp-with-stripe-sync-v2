<?php

namespace Tests\Feature\Requests\Admin\Category;

use App\Http\Requests\Admin\Category\DeleteCategoryRequest;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class DeleteCategoryRequestTest extends TestCase
{
    use RefreshDatabase;

    private DeleteCategoryRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new DeleteCategoryRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $category = Category::factory()->create();

        $validator = Validator::make([
            'category_id' => $category->id,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_category_id_field_is_not_exists()
    {
        $validator = Validator::make([], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_category_id_is_string()
    {
        $validator = Validator::make([
            'category_id' => 'category id',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_category_is_not_exists()
    {
        Category::factory()->create();

        $validator = Validator::make([
            'category_id' => 100,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
