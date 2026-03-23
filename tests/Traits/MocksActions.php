<?php

namespace Tests\Traits;

trait MocksActions
{
    protected function mockAction(
        string $actionClass,
        array $with = [],
        mixed $return = null,
        int $times = 1
    ): void {
        $mock = \Mockery::mock($actionClass);

        $expectation = $mock
            ->shouldReceive('handle')
            ->times($times);

        if (!empty($with)) {
            $expectation->with(...$with);
        }

        if (!is_null($return)) {
            $expectation->andReturn($return);
        }

        $this->app->instance(
            $actionClass,
            $mock
        );
    }
}
