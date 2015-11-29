<?php

namespace App\Miners;

use App\Activities\ActivityType;
use App\Exceptions\InvalidArgumentException;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;

/**
 * Class UserStatusMiner
 * @package App\Miners
 */
class UserStatusMiner
{

    /**
     * @var UserActivityRepositoryInterface
     */
    private $activityRepository;

    /**
     * UserStatusMiner constructor.
     * @param UserActivityRepositoryInterface $activityRepository
     */
    public function __construct(UserActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * @param User $user
     * @return bool
     * @throws InvalidArgumentException
     */
    public function isUserInfoOutdated(User $user)
    {
        if ('' === (string)$user->getLastName()) {
            return true;
        }

        if ('' === (string)$user->getPhoto()) {
            return true;
        }

        $this->activityRepository->getActivity(new ActivityType(ActivityType::SYNC), $user->getId());

        return true;
    }
}
