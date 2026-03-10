<?php

namespace Tests\Feature\Requests\User\Search;

use App\Http\Requests\User\Search\SearchPublishedProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SearchPublishedProductRequestTest extends TestCase
{
    use RefreshDatabase;

    private SearchPublishedProductRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new SearchPublishedProductRequest();
    }

    public function test_validation_passes_with_valid_keyword()
    {
        $validator = Validator::make(
            ['keyword' => 'iphone'],
            $this->request->rules()
        );

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_when_keyword_is_string()
    {
        $validator = Validator::make(
            ['keyword' => 123],
            $this->request->rules()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('keyword', $validator->errors()->toArray());
    }
}
