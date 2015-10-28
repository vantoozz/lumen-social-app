<?php

namespace App\Repositories\Users;

/**
 * Interface UsersRepositoryInterface
 * @package App\Repositories\Users
 */
use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\RepositoryInterface;
use App\Resources\User;


/**
 * Interface UsersRepositoryInterface
 * @package App\Repositories\Users
 */
interface UsersRepositoryInterface extends RepositoryInterface
{

    /**
     * @param  int $provider
     * @param  int $provider_id
     * @throws NotFoundInRepositoryException
     * @return User
     */
    public function getByProviderId($provider, $provider_id);
}
