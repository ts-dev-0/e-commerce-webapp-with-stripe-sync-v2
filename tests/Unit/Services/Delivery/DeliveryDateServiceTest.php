<?php

namespace Tests\Unit\Services\Delivery;

use Tests\TestCase;
use App\Services\Delivery\DeliveryDateService;
use Carbon\Carbon;

class DeliveryDateServiceTest extends TestCase
{
    private DeliveryDateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DeliveryDateService();
    }

    public function test_it_generates_correct_number_of_dates(): void
    {
        $dates = $this->service->generate(2, 5);

        $this->assertCount(5, $dates);
    }

    public function test_it_starts_from_correct_lead_time(): void
    {
        Carbon::setTestNow('2026-03-19');

        $dates = $this->service->generate(2, 5);

        $this->assertEquals('2026-03-21', $dates[0]);
    }

    public function test_dates_are_strings(): void
    {
        $dates = $this->service->generate();

        foreach ($dates as $date) {
            $this->assertIsString($date);
        }
    }

    public function test_dates_are_sequential(): void
    {
        Carbon::setTestNow('2026-03-19');

        $dates = $this->service->generate(2, 3);

        $this->assertEquals('2026-03-21', $dates[0]);
        $this->assertEquals('2026-03-22', $dates[1]);
        $this->assertEquals('2026-03-23', $dates[2]);
    }
}