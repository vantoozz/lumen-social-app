<?php

namespace App\Repositories\Resources\Users;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\Resources\DatabaseResourceRepository;
use App\Resources\User;

/**
 * Class DatabaseUsersRepository
 * @package App\Repositories\Resources\Users
 */
class DatabaseUsersRepository extends DatabaseResourceRepository implements UsersRepositoryInterface
{

    /**
     * @var string
     */
    protected static $table = 'users';

    /**
     * @param  int $provider
     * @param  int $providerId
     *
     * @return User
     * @throws NotFoundInRepositoryException
     * @throws \InvalidArgumentException
     */
    public function getByProviderId($provider, $providerId)
    {
        $data = $this->connection
            ->table(static::$table)
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if (null === $data) {
            throw new NotFoundInRepositoryException('User not found');
        }

        return $this->hydrator->hydrate((array)$data);
    }
}
