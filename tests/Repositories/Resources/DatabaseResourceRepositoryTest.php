<?php

namespace App\Repositories\Resources;


use App\Hydrators\HydratorInterface;
use App\Repositories\Resources\Users\DatabaseUsersRepository;
use App\Resources\User;
use App\TestCase;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;

class DatabaseResourceRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_finds_resource_by_id()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = static::getMock(HydratorInterface::class);
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $hydratedUser = new User();

        $hydratedUser->populate(['provider' => 'some provider', 'first_name' => 'some name']);


        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::once())
            ->method('find')
            ->willReturn((object)['id' => 12345, 'first_name' => 'some name']);

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with(['id' => 12345, 'first_name' => 'some name'])
            ->willReturn($hydratedUser);


        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);
        $user = $repository->getById(123);
        static::assertSame($hydratedUser, $user);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\NotFoundInRepositoryException
     * @expectedExceptionMessage Not found
     */
    public function it_throws_exception_if_user_not_found_by_id()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = static::getMock(HydratorInterface::class);
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $hydratedUser = new User();

        $hydratedUser->populate(['provider' => 'some provider', 'first_name' => 'some name']);


        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::once())
            ->method('find')
            ->willReturn(null);


        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);
        $repository->getById(123);
    }


    /**
     * @test
     */
    public function it_creates_resource()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = static::getMock(HydratorInterface::class);
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $user = new User();
        $user->populate(['provider' => 'some provider', 'first_name' => 'some name']);

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::once())
            ->method('insertGetId')
            ->with(static::logicalAnd(static::arrayHasKey('updated_at'), static::arrayHasKey('created_at')))
            ->willReturn(1234567);

        $hydrator
            ->expects(static::once())
            ->method('extract')
            ->with($user)
            ->willReturn(['provider' => 'some provider', 'first_name' => 'some name']);


        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);

        $storedUser = $repository->store($user);
        static::assertInstanceOf(User::class, $storedUser);
        static::assertSame(1234567, $storedUser->getId());
    }
}
