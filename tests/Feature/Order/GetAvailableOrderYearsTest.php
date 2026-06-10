<?php

namespace Tests\Feature\Order;

use App\Actions\Order\GetAvailableOrderYears;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GetAvailableOrderYearsTest extends TestCase
{
    use RefreshDatabase;

    private GetAvailableOrderYears $action;
    private User $user2020;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new GetAvailableOrderYears();
        $this->user2020 = User::factory()->create([
            'created_at' => Carbon::create(2020, 4, 11),
        ]);
    }

    public function test_it_returns_available_order_years_data()
    {
        $timeFilter = [
            [
                'label' => '2026',
                'value' => '2026',
            ],
            [
                'label' => '2025',
                'value' => '2025',
            ],
            [
                'label' => '2024',
                'value' => '2024',
            ],
            [
                'label' => '2023',
                'value' => '2023',
            ],
            [
                'label' => '2022',
                'value' => '2022',
            ],
            [
                'label' => '2021',
                'value' => '2021',
            ],
            [
                'label' => '2020',
                'value' => '2020',
            ],
        ];

        $result = $this->action->handle($this->user2020);

        $this->assertEquals($timeFilter, $result);
    }
}
