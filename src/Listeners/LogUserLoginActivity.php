<?php

namespace App\Listeners;

use App\Activities\ActivityType;
use App\Activities\UserActivity;
use App\Exceptions\InvalidArgumentException;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use Carbon\Carbon;

/**
 * Class LogUserLoginActivity
 * @package App\Listeners
 */
class LogUserLoginActivity
{

    /**
     * @var UserActivityRepositoryInterface
     */
    private $repository;

    /**
     * @param UserActivityRepositoryInterface $repository
     */
    public function __construct(UserActivityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @throws InvalidArgumentException
     */
    public function handle(User $user)
    {
        $activity = new UserActivity();
        $activity->setType(new ActivityType(ActivityType::LOGIN));
        $activity->setDatetime(new Carbon);
        $activity->setUserId($user->getId());

        $this->repository->store($activity);
    }
}
