<?php

namespace App\Repositories\Resources\Users;

use App\Resources\User;
use App\TestCase;


class DummyUserRepositoryTest extends TestCase
{
    /**
     * @test
     * @expectedException     \App\Exceptions\NotFoundInRepositoryException
     */
    public function it_throws_exception_when_searching_resource_by_id()
    {
        $repository = new DummyUserRepository;
        $repository->getById(123);
    }

    /**
     * @test
     */
    public function it_stores_resource()
    {
        $repository = new DummyUserRepository;
        $user = new User;
        $user->setId(12345);
        $storedUser = $repository->store($user);
        static::assertSame($storedUser, $user);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\NotFoundInRepositoryException
     */
    public function it_throws_exception_when_searching_user_by_provider_id()
    {
        $repository = new DummyUserRepository;
        $repository->getByProviderId('some provider', 123);
    }
}
