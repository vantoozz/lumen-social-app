<?php

namespace App\Listeners;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\Exceptions\FactoryException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderLocator;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SyncUserDataIfNeeded
 * @package App\Listeners
 */
class SyncUserDataIfNeeded implements ShouldQueue
{
    const SYNC_FREQUENCY = -1;

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
     * @param UserActivityRepositoryInterface $activitiesRepository
     * @param UsersRepositoryInterface $usersRepository
     * @param SocialProviderLocator $providerFactory
     */
    public function __construct(
        UserActivityRepositoryInterface $activitiesRepository,
        UsersRepositoryInterface $usersRepository,
        SocialProviderLocator $providerFactory
    ) {
        $this->activitiesRepository = $activitiesRepository;
        $this->providerFactory = $providerFactory;
        $this->usersRepository = $usersRepository;
    }

    /**
     * @param User $user
     * @throws FactoryException
     * @throws InvalidArgumentException
     * @throws RepositoryException
     */
    public function handle(User $user)
    {
        if (!$this->isUserInfoOutdated($user)) {
            return;
        }

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
        $providerUser = $provider->getUserByProviderId($user->getProviderId());
        return $providerUser;
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

    /**
     * @param User $user
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function isUserInfoOutdated(User $user)
    {
        if ('' === (string)$user->getLastName()) {
            return true;
        }

        if ('' === (string)$user->getPhoto()) {
            return true;
        }

        try {
            $activity = $this->activitiesRepository->getActivity(new ActivityType(ActivityType::SYNC), $user->getId());
        } catch (NotFoundInRepositoryException $e) {
            return true;
        }

        return $activity->getDatetime()->diff(new Carbon)->days >= self::SYNC_FREQUENCY;
    }
}
