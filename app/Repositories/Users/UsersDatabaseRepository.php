<?php

namespace App\Repositories\Users;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\DatabaseRepository;
use App\User;

/**
 * Class UsersDatabaseRepository
 * @package App\Repositories\Users
 */
class UsersDatabaseRepository extends DatabaseRepository implements UsersRepositoryInterface
{

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @param  int $provider
     * @param  int $provider_id
     * @return User
     * @throws NotFoundInRepositoryException
     */
    public function getByProviderId($provider, $provider_id)
    {

        $results = $this->db->select(
            'SELECT * FROM `' . $this->table . '`  WHERE `provider` = :provider AND `provider_id` = :provider_id LIMIT 1',
            ['provider' => $provider, 'provider_id' => $provider_id]
        );
        if (empty($results)) {
            throw new NotFoundInRepositoryException('User not found');
        }

        return $this->makeModel((array)$results[0]);
    }

    /**
     * @param  array $data
     * @return User
     */
    protected function makeModel(array $data)
    {
        return new User($data);
    }

}
