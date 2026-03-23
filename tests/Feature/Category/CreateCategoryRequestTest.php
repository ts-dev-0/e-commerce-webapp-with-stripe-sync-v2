<?php

namespace Tests\Feature\Category;

use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CreateCategoryRequestTest extends TestCase
{
    private CreateCategoryRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new CreateCategoryRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $validator = Validator::make([
            'name' => 'Test category',
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_name_field_is_not_exists()
    {
        $validator = Validator::make([], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_integer()
    {
        $validator = Validator::make([
            'name' => 1,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }

    public function test_validation_fails_when_name_is_more_than_255()
    {
        $validator = Validator::make([
            'name' => str_repeat('a', 256),
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
