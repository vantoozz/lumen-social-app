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
            $this->dispatch(new UpdateUserCdnPhotoIfNeeded($this->user));

            return;
        }

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = app('social.' . $this->user->getProvider());

        $user = $provider->getUserByProviderId($this->user->getProviderId());

        $updatedInfo = $this->getFilteredUserInfo($user);
        $this->user->fill($updatedInfo);

        $this->user->setLastSyncNow();

        /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
        $usersRepository = app('App\Repositories\Users\UsersRepositoryInterface');
        $usersRepository->save($this->user);

        $this->dispatch(new UpdateUserCdnPhotoIfNeeded($this->user));
    }

    /**
     * @param User $user
     * @return array
     */
    private function getFilteredUserInfo(User $user)
    {
        return array_filter(
            $user->toArray(),
            function ($key) {
                return in_array(
                    $key,
                    [
                        User::FIELD_FIRST_NAME,
                        User::FIELD_LAST_NAME,
                        User::FIELD_SEX,
                        User::FIELD_BIRTH_DATE,
                        User::FIELD_PHOTO,
                    ]
                );
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
