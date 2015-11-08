<?php

namespace App\Repositories\UserActivity;

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
}
