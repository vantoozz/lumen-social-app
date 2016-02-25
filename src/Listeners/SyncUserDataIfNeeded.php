<?php

namespace App\Listeners;

use App\Exceptions\FactoryException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\RepositoryException;
use App\Jobs\JobsLocator;
use App\Jobs\SyncUserData;
use App\Miners\UserStatus\UserStatusMinerInterface;
use App\Resources\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SyncUserDataIfNeeded
 * @package App\Listeners
 */
class SyncUserDataIfNeeded implements ShouldQueue
{
    /**
     * @var UserStatusMinerInterface
     */
    private $userStatusMiner;
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var JobsLocator
     */
    private $jobsLocator;

    /**
     * @param UserStatusMinerInterface $userStatusMiner
     * @param Dispatcher $dispatcher
     * @param JobsLocator $jobsLocator
     */
    public function __construct(
        UserStatusMinerInterface $userStatusMiner,
        Dispatcher $dispatcher,
        JobsLocator $jobsLocator
    ) {
    
        $this->userStatusMiner = $userStatusMiner;
        $this->dispatcher = $dispatcher;
        $this->jobsLocator = $jobsLocator;
    }

    /**
     * @param Login $event
     * @throws FactoryException
     * @throws InvalidArgumentException
     * @throws RepositoryException
     */
    public function handle(Login $event)
    {
        /** @var User $user */
        $user = $event->user;

        if (!$this->userStatusMiner->isUserInfoOutdated($user)) {
            return;
        }

        $job = $this->jobsLocator->build(SyncUserData::class, $user);
        $this->dispatcher->dispatch($job);
    }
}
