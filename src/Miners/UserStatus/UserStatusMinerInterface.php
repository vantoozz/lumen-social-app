<?php

namespace App\Miners\UserStatus;

use App\Resources\User;

/**
 * Interface UserStatusMinerInterface
 * @package App\Miners\UserStatus
 */
interface UserStatusMinerInterface
{
    /**
     * @param User $user
     * @return bool
     */
    public function isUserInfoOutdated(User $user);
}
