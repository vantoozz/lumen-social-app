<?php declare(strict_types = 1);

namespace App\Repositories\UserActivity;

use App\Activities\ActivityType;
use App\Activities\UserActivity;

/**
 * Interface UserActivityRepositoryInterface
 * @package App\Repositories\UserActivity
 */
interface UserActivityRepositoryInterface
{
    /**
     * @param UserActivity $activity
     */
    public function store(UserActivity $activity);

    /**
     * @param ActivityType $activityType
     * @param $userId
     * @return UserActivity
     */
    public function getActivity(ActivityType $activityType, $userId);
}
