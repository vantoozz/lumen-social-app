<?php

namespace App\Auth;

use App\Exceptions\AppException;
use App\Repositories\Users\UsersRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

/**
 * Class DbUserProvider
 * @package App\Auth
 */
class DbUserProvider implements UserProvider
{

    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;

    /**
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        return $this->usersRepository->getById($identifier);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @throws AppException
     * @return Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        throw new AppException('Invalid method');
    }

    /**
     * @param Authenticatable $user
     * @param string $token
     * @throws AppException
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new AppException('Invalid method');
    }

    /**
     * @param array $credentials
     * @throws AppException
     * @return Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        throw new AppException('Invalid method');
    }


    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @throws AppException
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new AppException('Invalid method');
    }
}
