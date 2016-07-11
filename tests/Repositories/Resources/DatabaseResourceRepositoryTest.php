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
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(HydratorInterface::class);
        $builder = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);


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
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(HydratorInterface::class);
        $builder = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();
        $hydratedUser = new User('some provider', 123);

        $hydratedUser->populate(['first_name' => 'some name']);


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
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(HydratorInterface::class);
        $builder = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $user = new User('some provider', 123);

        $user->populate(['first_name' => 'some name']);

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


    /**
     * @test
     */
    public function it_updates_resource()
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(HydratorInterface::class);
        $builder = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $user = new User('some provider', 123);
        $user->populate(['id' => 12345, 'first_name' => 'some name']);

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);


        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 12345)
            ->willReturnSelf();

        $builder
            ->expects(static::once())
            ->method('update')
            ->with(static::logicalAnd(
                static::arrayHasKey('updated_at'),
                static::logicalNot(static::arrayHasKey('created_at'))
            ))
            ->willReturn(1);

        $hydrator
            ->expects(static::once())
            ->method('extract')
            ->with($user)
            ->willReturn(['id' => 12345, 'provider' => 'some provider', 'first_name' => 'some name']);


        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);

        $storedUser = $repository->store($user);
        static::assertInstanceOf(User::class, $storedUser);
        static::assertSame(12345, $storedUser->getId());
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\RepositoryException
     * @expectedExceptionMessage some error
     * @expectedExceptionCode 123
     */
    public function it_throws_exception_while_updating_resource()
    {
        $connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = $this->createMock(HydratorInterface::class);
        $builder = $this->getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $user = new User('some provider', 123);
        $user->populate(['id' => 12345, 'first_name' => 'some name']);

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);


        $builder
            ->expects(static::once())
            ->method('where')
            ->with('id', 12345)
            ->willThrowException(new \InvalidArgumentException('some error', 123));


        $hydrator
            ->expects(static::once())
            ->method('extract')
            ->with($user)
            ->willReturn(['id' => 12345, 'provider' => 'some provider', 'first_name' => 'some name']);


        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);

        $repository->store($user);
    }
}
