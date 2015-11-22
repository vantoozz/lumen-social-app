<?php

namespace App\Repositories\Resources\Users;

use App\Hydrators\HydratorInterface;
use App\Resources\User;
use App\TestCase;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;

class DatabaseUsersRepositoryTest extends TestCase
{
    /**
     * @test
     * @expectedException     \App\Exceptions\NotFoundInRepositoryException
     * @expectedExceptionMessage User not found
     */
    public function it_throws_exception_if_user_not_found_by_provider_id()
    {
        $connection = static::getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $hydrator = static::getMock(HydratorInterface::class);
        $builder = static::getMockBuilder(Builder::class)->disableOriginalConstructor()->getMock();

        $connection
            ->expects(static::once())
            ->method('table')
            ->with('users')
            ->willReturn($builder);

        $builder
            ->expects(static::at(0))
            ->method('where')
            ->with('provider', 'some provider')
            ->willReturnSelf();
        $builder
            ->expects(static::at(1))
            ->method('where')
            ->with('provider_id', 123)
            ->willReturnSelf();
        $builder
            ->expects(static::once())
            ->method('first')
            ->willReturn(null);

        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);
        $repository->getByProviderId('some provider', 123);
    }

    /**
     * @test
     */
    public function it_finds_by_provider_id()
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
            ->expects(static::at(0))
            ->method('where')
            ->with('provider', 'some provider')
            ->willReturnSelf();
        $builder
            ->expects(static::at(1))
            ->method('where')
            ->with('provider_id', 123)
            ->willReturnSelf();
        $builder
            ->expects(static::once())
            ->method('first')
            ->willReturn((object)['id' => 12345, 'first_name' => 'some name']);

        $hydrator
            ->expects(static::once())
            ->method('hydrate')
            ->with(['id' => 12345, 'first_name' => 'some name'])
            ->willReturn($hydratedUser);

        /** @var Connection $connection */
        /** @var HydratorInterface $hydrator */
        $repository = new DatabaseUsersRepository($connection, $hydrator);
        $user = $repository->getByProviderId('some provider', 123);
        static::assertSame($hydratedUser, $user);

    }
}
