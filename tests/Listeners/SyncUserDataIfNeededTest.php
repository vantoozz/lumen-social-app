<?php

namespace App\Listeners;

use App\Jobs\JobsLocator;
use App\Jobs\SyncUserData;
use App\Miners\UserStatus\UserStatusMinerInterface;
use App\Resources\User;
use App\TestCase;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Bus\Dispatcher;
use PHPUnit_Framework_MockObject_MockObject;

class SyncUserDataIfNeededTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $jobsLocator;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $dispatcher;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $userStatusMiner;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->createMock(Dispatcher::class);
        $this->userStatusMiner = $this->createMock(UserStatusMinerInterface::class);
        $this->jobsLocator = $this->getMockBuilder(JobsLocator::class)
            ->disableOriginalConstructor()
            ->getMock();

    }

    /**
     * @test
     */
    public function it_do_nothing_if_user_is_up_to_date()
    {

        $user = new User('some provider', 123);

        $this->userStatusMiner
            ->expects(static::once())
            ->method('isUserInfoOutdated')
            ->with($user)
            ->willReturn(false);

        $this->dispatcher
            ->expects(static::never())
            ->method('dispatch');

        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;
        /** @var JobsLocator $jobsLocator */
        $jobsLocator = $this->jobsLocator;
        /** @var UserStatusMinerInterface $userStatusMiner */
        $userStatusMiner = $this->userStatusMiner;

        $listener = new SyncUserDataIfNeeded($userStatusMiner, $dispatcher, $jobsLocator);

        $event = new Login($user, false);
        $listener->handle($event);
    }

    /**
     * @test
     */
    public function it_updates_outdated_user()
    {
        $user = new User('some provider', 123);

        $job = new SyncUserData($user);

        $this->userStatusMiner
            ->expects(static::once())
            ->method('isUserInfoOutdated')
            ->with($user)
            ->willReturn(true);

        $this->jobsLocator
            ->expects(static::once())
            ->method('build')
            ->with(SyncUserData::class, $user)
            ->willReturn($job);

        $this->dispatcher
            ->expects(static::once())
            ->method('dispatch')
            ->with($job);


        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->dispatcher;
        /** @var JobsLocator $jobsLocator */
        $jobsLocator = $this->jobsLocator;
        /** @var UserStatusMinerInterface $userStatusMiner */
        $userStatusMiner = $this->userStatusMiner;

        $listener = new SyncUserDataIfNeeded($userStatusMiner, $dispatcher, $jobsLocator);

        $event = new Login($user, false);
        $listener->handle($event);
    }
}
