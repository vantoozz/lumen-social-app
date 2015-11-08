<?php

namespace App\Listeners;

use App\Exceptions\FactoryException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderLocator;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SyncUserDataIfNeeded
 * @package App\Listeners
 */
class SyncUserDataIfNeeded implements ShouldQueue
{
    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;
    /**
     * @var SocialProviderLocator
     */
    private $providerFactory;

    /**
     * @param UsersRepositoryInterface $usersRepository
     * @param SocialProviderLocator $providerFactory
     */
    public function __construct(UsersRepositoryInterface $usersRepository, SocialProviderLocator $providerFactory)
    {
        $this->usersRepository = $usersRepository;
        $this->providerFactory = $providerFactory;
    }

    /**
     * @param User $user
     * @throws FactoryException
     */
    public function handle(User $user)
    {
        if (!$user->isSyncNeeded()) {
            return;
        }

        $providerName = $user->getProvider();
        $provider = $this->providerFactory->build($providerName);

        $providerUser = $provider->getUserByProviderId($user->getProviderId());

        $updatedInfo = $this->getFilteredUserInfo($providerUser);
        $user->populate($updatedInfo);

        $user->setLastSyncNow();

        $this->usersRepository->store($user);
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
