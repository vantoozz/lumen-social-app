<?php

namespace App\Repositories\UserActivity;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\TestCase;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;

class DatabaseUserActivityRepositoryTest extends TestCase
{

    /**
     * @test
     */
    public function it_stores_login_activity()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);


        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 123)
            ->willReturnSelf();

        $builder
            ->expects(static::once())
            ->method('update')
            ->with(['last_login_at' => '2015-01-01 12:23:34'])
            ->willReturn(1);

        $activity = new UserActivity();
        $activity->setType(new ActivityType(ActivityType::LOGIN));
        $activity->setUserId(123);
        $activity->setDatetime(new Carbon('2015-01-01 12:23:34', 'UTC'));

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $repository->store($activity);
    }

    /**
     * @test
     */
    public function it_stores_sync_activity()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);


        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 123)
            ->willReturnSelf();

        $builder
            ->expects(static::once())
            ->method('update')
            ->with(['last_sync_at' => '2015-01-01 12:23:34'])
            ->willReturn(1);

        $activity = new UserActivity();
        $activity->setType(new ActivityType(ActivityType::SYNC));
        $activity->setUserId(123);
        $activity->setDatetime(new Carbon('2015-01-01 12:23:34', 'UTC'));

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $repository->store($activity);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\RepositoryException
     * @expectedExceptionMessage some error
     * @expectedExceptionCode 123
     */
    public function it_throws_exception_while_storing_activity()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 123)
            ->willThrowException(new \InvalidArgumentException('some error', 123));

        $activity = new UserActivity();
        $activity->setType(new ActivityType(ActivityType::SYNC));
        $activity->setUserId(123);
        $activity->setDatetime(new Carbon('2015-01-01 12:23:34'));

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $repository->store($activity);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Activity type not set
     */
    public function it_throws_exception_if_activity_type_not_set()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        $activity = new UserActivity();
        $activity->setUserId(123);

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $repository->store($activity);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage No field for this activity type
     */
    public function it_throws_exception_if_bad_activity_type()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();

        /** @var ActivityType $activityType */
        $activityType = static::getMockBuilder(ActivityType::class)->disableOriginalConstructor()->getMock();

        $activity = new UserActivity();
        $activity->setUserId(123);
        $activity->setType($activityType);

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $repository->store($activity);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\NotFoundInRepositoryException
     * @expectedExceptionMessage No activity found
     */
    public function it_thrown_an_exception_if_no_activity_found()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 123)
            ->willReturnSelf();

        $builder
            ->expects(static::once())
            ->method('value')
            ->with('last_login_at')
            ->willReturn(null);

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $repository->getActivity(new ActivityType(ActivityType::LOGIN), 123);
    }
    /**
     * @test
     */
    public function it_gets_activity()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 123)
            ->willReturnSelf();

        $builder
            ->expects(static::once())
            ->method('value')
            ->with('last_login_at')
            ->willReturn('2015-01-01 11:11:11');

        /** @var Connection $connection */
        $repository = new DatabaseUserActivityRepository($connection);
        $activity = $repository->getActivity(new ActivityType(ActivityType::LOGIN), 123);

        static::assertInstanceOf(UserActivity::class, $activity);
        static::assertSame(ActivityType::LOGIN, $activity->getType()->getType());
        static::assertSame(123, $activity->getUserId());
        static::assertSame('20150101', $activity->getDatetime()->format('Ymd'));
    }
}
