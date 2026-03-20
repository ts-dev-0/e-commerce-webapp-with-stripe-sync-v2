<?php

namespace Tests\Feature\Requests\User\Address;

use App\Http\Requests\StoreAddressRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreAddressRequestTest extends TestCase
{
    private StoreAddressRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new StoreAddressRequest();
    }

    public function test_validation_passes_with_valid_data()
    {
        $validator = Validator::make([
            'full_name' => 'test name',
            'postal_code' => '1234567',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '09000000000',
            'is_default' => true,
        ], $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_rejects_invalid_prefecture()
    {
        $validator = Validator::make([
            'full_name' => 'test name',
            'postal_code' => '123-4567',
            'prefecture' => '東京',
            'city' => '新宿区',
            'address_line' => '1-2-3-101',
            'phone_number' => '090-0000-0000',
            'is_default' => true,
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
