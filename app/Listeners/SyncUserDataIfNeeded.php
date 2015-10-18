<?php

namespace App\Listeners;

use App\Repositories\Users\UsersRepositoryInterface;
use App\User;
use Laravel\Lumen\Routing\DispatchesJobs;

/**
 * Class SyncUserDataIfNeeded
 * @package App\Listeners
 */
class SyncUserDataIfNeeded //implements ShouldQueue
{

    use DispatchesJobs;


    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;

    /**
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * @param User $user
     */
    public function handle(User $user)
    {
        if (!$user->isSyncNeeded()) {
            return;
        }

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = app('social.' . $user->getProvider());

        $providerUser = $provider->getUserByProviderId($user->getProviderId());

        $updatedInfo = $this->getFilteredUserInfo($providerUser);
        $user->fill($updatedInfo);

        $user->setLastSyncNow();

        $this->usersRepository->save($user);
    }

    /**
     * @param User $user
     *
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
                    ],
                    true
                );
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
