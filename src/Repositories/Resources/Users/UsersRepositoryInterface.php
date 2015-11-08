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
     * @param  int $provider_id
     * @throws NotFoundInRepositoryException
     * @return User
     */
    public function getByProviderId($provider, $provider_id);
}
