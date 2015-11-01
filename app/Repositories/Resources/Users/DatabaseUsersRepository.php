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
     * @param  int $provider_id
     *
     * @return User
     * @throws NotFoundInRepositoryException
     */
    public function getByProviderId($provider, $provider_id)
    {

        $results = $this->db->select(
            'SELECT * FROM `' . self::$table . '`  WHERE `provider` = :provider AND `provider_id` = :provider_id LIMIT 1',
            ['provider' => $provider, 'provider_id' => $provider_id]
        );
        if (0 === count($results)) {
            throw new NotFoundInRepositoryException('User not found');
        }

        return $this->hydrator->hydrate((array)$results[0]);
    }
}
