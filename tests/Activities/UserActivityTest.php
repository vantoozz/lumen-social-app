<?php

namespace App\Activities;

use App\TestCase;
use Carbon\Carbon;

class UserActivityTest extends TestCase
{

    /**
     * @test
     */
    public function it_stores_type()
    {
        $userActivity = new UserActivity();
        $userActivity->setType(new ActivityType('sync'));
        static::assertInstanceOf(ActivityType::class, $userActivity->getType());
        static::assertSame('sync', $userActivity->getType()->getType());
    }

    /**
     * @test
     */
    public function it_stores_datetime()
    {
        $userActivity = new UserActivity();
        $userActivity->setDatetime(new Carbon('2015-01-01 11:11:11'));
        static::assertInstanceOf(Carbon::class, $userActivity->getDatetime());
        static::assertSame('2015-01-01 11:11:11', $userActivity->getDatetime()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function it_stores_user_id()
    {
        $userActivity = new UserActivity();
        $userActivity->setUserId(123);
        static::assertSame(123, $userActivity->getUserId());
    }

    /**
     * @test
     */
    public function it_stores_user_id_as_integer()
    {
        $userActivity = new UserActivity();
        $userActivity->setUserId('123');
        static::assertSame(123, $userActivity->getUserId());
    }
}
