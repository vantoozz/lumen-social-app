<?php

namespace App\Repositories\Resources\Users;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\Resources\DummyResourceRepository;
use App\Resources\User;

/**
 * Class DummyUserRepository
 * @package App\Repositories\Resources\Users
 */
class DummyUserRepository extends DummyResourceRepository implements UsersRepositoryInterface
{
    /**
     * @param  int $provider
     * @param  int $provider_id
     * @throws NotFoundInRepositoryException
     * @return User
     */
    public function getByProviderId($provider, $provider_id)
    {
        throw new NotFoundInRepositoryException;
    }
}
