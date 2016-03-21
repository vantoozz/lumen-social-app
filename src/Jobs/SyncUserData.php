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
    private $providerLocator;
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
     * @param SocialProviderLocator $providerLocator
     * @throws InvalidArgumentException
     * @throws RepositoryException
     * @throws FactoryException
     */
    public function handle(
        UserActivityRepositoryInterface $activitiesRepository,
        UsersRepositoryInterface $usersRepository,
        SocialProviderLocator $providerLocator
    ) {
    
        $this->activitiesRepository = $activitiesRepository;
        $this->providerLocator = $providerLocator;
        $this->usersRepository = $usersRepository;
        
        $this->updateUser();
        $this->usersRepository->store($this->user);
        $this->createActivity();
    }

    /**
     * @throws FactoryException
     */
    protected function updateUser()
    {
        $providerName = $this->user->getProvider();
        $provider = $this->providerLocator->build($providerName);

        $provider->fillUserData($this->user);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function createActivity()
    {
        $activity = new UserActivity;
        $activity->setType(new ActivityType(ActivityType::SYNC));
        $activity->setUserId($this->user->getId());
        $activity->setDatetime(new Carbon);

        $this->activitiesRepository->store($activity);
    }
}
