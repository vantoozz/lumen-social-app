<?php

namespace App\Repositories\Resources\Users;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\Resources\ResourceRepositoryInterface;
use App\Resources\User;

/**
 * Interface UsersRepositoryInterface
 * @package App\Repositories\Resources\Users
 */
interface UsersRepositoryInterface extends ResourceRepositoryInterface
{

    /**
     * @param  int $provider
     * @param  int $providerId
     * @throws NotFoundInRepositoryException
     * @return User
     */
    public function getByProviderId($provider, $providerId);

    /**
     * @param User $user
     * @return User
     */
    public function merge(User $user);
}
