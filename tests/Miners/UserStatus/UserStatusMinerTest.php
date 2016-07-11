<?php

namespace App\Miners\UserStatus;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use App\TestCase;
use Carbon\Carbon;

class UserStatusMinerTest extends TestCase
{
    /**
     * @test
     */
    public function it_mark_user_outdated_if_user_has_no_last_name()
    {
        $repository = $this->createMock(UserActivityRepositoryInterface::class);
        /** @var  UserActivityRepositoryInterface $repository */
        $miner = new UserStatusMiner($repository);
        $user = new User('some provider', 123);
        $user->setLastName('');
        static::assertTrue($miner->isUserInfoOutdated($user));
    }

    /**
     * @test
     */
    public function it_mark_user_outdated_if_user_has_no_photo()
    {
        $repository = $this->createMock(UserActivityRepositoryInterface::class);
        /** @var  UserActivityRepositoryInterface $repository */
        $miner = new UserStatusMiner($repository);
        $user = new User('some provider', 123);
        $user->setLastName('last name');
        $user->setPhoto('');
        static::assertTrue($miner->isUserInfoOutdated($user));
    }

    /**
     * @test
     */
    public function it_mark_user_outdated_if_user_last_activity_is_too_old()
    {
        $repository = $this->createMock(UserActivityRepositoryInterface::class);

        $user = new User('some provider', 123);
        $user->setLastName('last name');
        $user->setPhoto('photo');
        $user->setId(123);

        $activity = new UserActivity;
        $activity->setDatetime(new Carbon('2001-01-01'));
        $repository
            ->expects(static::once())
            ->method('getActivity')
            ->with(new ActivityType(ActivityType::SYNC), 123)
            ->willReturn($activity);

        /** @var  UserActivityRepositoryInterface $repository */
        $miner = new UserStatusMiner($repository);
        static::assertTrue($miner->isUserInfoOutdated($user));
    }
    /**
     * @test
     */
    public function it_mark_user_outdated_if_no_user_activity()
    {
        $repository = $this->createMock(UserActivityRepositoryInterface::class);

        $user = new User('some provider', 123);
        $user->setLastName('last name');
        $user->setPhoto('photo');
        $user->setId(123);

        $repository
            ->expects(static::once())
            ->method('getActivity')
            ->with(new ActivityType(ActivityType::SYNC), 123)
            ->willThrowException(new NotFoundInRepositoryException);

        /** @var  UserActivityRepositoryInterface $repository */
        $miner = new UserStatusMiner($repository);
        static::assertTrue($miner->isUserInfoOutdated($user));
    }
}
