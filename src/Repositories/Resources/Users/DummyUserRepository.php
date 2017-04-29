<?php declare(strict_types = 1);

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
     * @param  int $providerId
     * @throws NotFoundInRepositoryException
     * @return User
     */
    public function getByProviderId($provider, $providerId)
    {
        throw new NotFoundInRepositoryException;
    }
}
