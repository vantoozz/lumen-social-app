<?php

namespace App\Repositories\Resources\Users;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Repositories\Resources\DatabaseResourceRepository;
use App\Resources\User;
use Carbon\Carbon;

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

    /**
     * @param User $user
     * @return User
     * @throws RepositoryException
     * @throws \InvalidArgumentException
     */
    public function merge(User $user)
    {
        try {
            $storedUser = $this->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $e) {
            return $this->store($user);
        }

        $user->setId($storedUser->getId());

        $fields = $user->getFillableFields();
        $merged = false;

        foreach ($fields as $field) {
            $merged = $this->mergeField($user, $storedUser, $field) || $merged;
        }

        if ($merged) {
            $this->store($user);
        }

        return $user;
    }


    private function mergeField(User $user, User $storedUser, $field)
    {
        $value = $user->{camel_case('get_' . $field)}();
        $storedValue = $storedUser->{camel_case('get_' . $field)}();

        if (!$value and $storedValue) {
            $user->{camel_case('set_' . $field)}($storedValue);
            return false;
        }

        if ($value instanceof Carbon and $storedValue instanceof Carbon and $value->eq($storedValue)) {
            return false;
        }

        return ($value !== $storedValue);
    }
}
