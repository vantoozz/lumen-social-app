<?php declare(strict_types = 1);

namespace App\Repositories\Resources;

use App\Resources\User;
use App\TestCase;

class DummyResourceRepositoryTest extends TestCase
{
    /**
     * @test
     * @expectedException     \App\Exceptions\NotFoundInRepositoryException
     */
    public function it_throws_exception_when_searching_resource_by_id()
    {
        $repository = new DummyResourceRepository;
        $repository->getById(123);
    }

    /**
     * @test
     */
    public function it_stores_resource()
    {
        $repository = new DummyResourceRepository;
        $user = new User('some provider', 123);
        $user->setId(12345);
        $storedUser = $repository->store($user);
        static::assertSame($storedUser, $user);
    }
}
