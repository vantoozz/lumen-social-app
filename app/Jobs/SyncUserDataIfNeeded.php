<?php

namespace App\Jobs;

use App\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Laravel\Lumen\Routing\DispatchesCommands;

/**
 * Class SyncUserDataIfNeeded
 * @package App\Jobs
 */
class SyncUserDataIfNeeded implements SelfHandling, ShouldBeQueued
{

    use DispatchesCommands;

    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     *
     */
    public function handle()
    {
        if (!$this->user->isSyncNeeded()) {
            return;
        }

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = app('social.' . $this->user->getProvider());

        $user = $provider->getUserByProviderId($this->user->getProviderId());
        $this->user->fill($user->overview());

        $this->user->setLastSyncNow();

        /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
        $usersRepository = app('App\Repositories\Users\UsersRepositoryInterface');
        $usersRepository->save($this->user);
    }
}
