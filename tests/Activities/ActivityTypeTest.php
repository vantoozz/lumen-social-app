<?php

namespace App\Activities;

use App\TestCase;

class ActivityTypeTest extends TestCase
{
    /**
     * @test
     */
    public function it_stores_type()
    {
        $activityType = new ActivityType('sync');
        static::assertSame('sync', $activityType->getType());
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage No such activity type
     */
    public function it_throws_exception_if_wrong_type()
    {
        new ActivityType('wrong type');
    }

}
