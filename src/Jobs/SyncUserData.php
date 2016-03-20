<?php

namespace App\Jobs;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\Exceptions\FactoryException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\RepositoryException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderLocator;
use Carbon\Carbon;

/**
 * Class SyncUserData
 * @package App\Jobs
 */
class SyncUserData implements JobInterface
{
    /**
     * @var UserActivityRepositoryInterface
     */
    private $activitiesRepository;
    /**
     * @var SocialProviderLocator
     */
    private $providerFactory;
    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;
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
     * @param UserActivityRepositoryInterface $activitiesRepository
     * @param UsersRepositoryInterface $usersRepository
     * @param SocialProviderLocator $providerFactory
     *
     * @throws InvalidArgumentException
     * @throws RepositoryException
     * @throws FactoryException
     */
    public function handle(
        UserActivityRepositoryInterface $activitiesRepository,
        UsersRepositoryInterface $usersRepository,
        SocialProviderLocator $providerFactory
    ) {
    

        $this->activitiesRepository = $activitiesRepository;
        $this->providerFactory = $providerFactory;
        $this->usersRepository = $usersRepository;

        $user = $this->user;

        $providerUser = $this->fetchProviderUser($user);
        $this->updateUser($user, $providerUser);
        $this->createActivity($user);
    }

    /**
     * @param User $user
     * @return User
     * @throws FactoryException
     */
    protected function fetchProviderUser(User $user)
    {
        $providerName = $user->getProvider();
        $provider = $this->providerFactory->build($providerName);

        return $provider->getUserByProviderId($user->getProviderId(), $user->getAccessToken());
    }

    /**
     * @param User $user
     * @param User $providerUser
     * @throws RepositoryException
     */
    protected function updateUser(User $user, User $providerUser)
    {
        $user->setFirstName($providerUser->getFirstName());
        $user->setLastName($providerUser->getLastName());
        $user->setSex($providerUser->getSex());
        $user->setPhoto($providerUser->getPhoto());

        $birthDate = $providerUser->getBirthDate();
        if ($birthDate instanceof Carbon) {
            $user->setBirthDate($birthDate);
        }

        $this->usersRepository->store($user);
    }

    /**
     * @param User $user
     * @throws InvalidArgumentException
     */
    protected function createActivity(User $user)
    {
        $activity = new UserActivity;
        $activity->setType(new ActivityType(ActivityType::SYNC));
        $activity->setUserId($user->getId());
        $activity->setDatetime(new Carbon);

        $this->activitiesRepository->store($activity);
    }
}
