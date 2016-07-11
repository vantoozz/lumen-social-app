<?php

namespace App\Repositories\Resources\Users;

use App\TestCase;

class DummyUserRepositoryTest extends TestCase
{
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
