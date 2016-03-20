<?php

namespace App\Miners\UserStatus;

use App\Activities\ActivityType;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use Carbon\Carbon;

/**
 * Class UserStatusMiner
 * @package App\Miners\UserStatus
 */
class UserStatusMiner implements UserStatusMinerInterface
{

    const SYNC_FREQUENCY = -1;

    /**
     * @var UserActivityRepositoryInterface
     */
    private $activitiesRepository;

    /**
     * UserStatusMiner constructor.
     * @param UserActivityRepositoryInterface $activitiesRepository
     */
    public function __construct(UserActivityRepositoryInterface $activitiesRepository)
    {
        $this->activitiesRepository = $activitiesRepository;
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

        try {
            $activity = $this->activitiesRepository->getActivity(new ActivityType(ActivityType::SYNC), $user->getId());
        } catch (NotFoundInRepositoryException $e) {
            return true;
        }

        return $activity->getDatetime()->diff(new Carbon)->days >= self::SYNC_FREQUENCY;
    }
}
