<?php declare(strict_types = 1);

namespace App\Auth;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\Resources\Users\DummyUserRepository;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use App\TestCase;

class DbUserProviderTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_user_by_id()
    {
        $storedUser = new User('provider', 123);
        $repository = $this->createMock(UsersRepositoryInterface::class);
        $repository
            ->expects(static::once())
            ->method('getById')
            ->with(123)
            ->willReturn($storedUser);

        /** @var UsersRepositoryInterface $repository */
        $userProvider = new DbUserProvider($repository);
        $user = $userProvider->retrieveById(123);
        static::assertSame($storedUser, $user);
    }


    /**
     * @test
     */
    public function it_returns_null_if_user_not_found()
    {
        $repository = $this->createMock(UsersRepositoryInterface::class);
        $repository
            ->expects(static::once())
            ->method('getById')
            ->with(123)
            ->willThrowException(new NotFoundInRepositoryException);

        /** @var UsersRepositoryInterface $repository */
        $userProvider = new DbUserProvider($repository);
        static::assertNull($userProvider->retrieveById(123));
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\AppException
     * @expectedExceptionMessage Invalid method
     */
    public function it_throws_exception_on_retrieveByToken()
    {
        $userProvider = new DbUserProvider(new DummyUserRepository);
        $userProvider->retrieveByToken(111, 'aaa');
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\AppException
     * @expectedExceptionMessage Invalid method
     */
    public function it_throws_exception_on_updateRememberToken()
    {
        $userProvider = new DbUserProvider(new DummyUserRepository);
        $userProvider->updateRememberToken(new User('provider', 123), 'aaa');
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\AppException
     * @expectedExceptionMessage Invalid method
     */
    public function it_throws_exception_on_retrieveByCredentials()
    {
        $userProvider = new DbUserProvider(new DummyUserRepository);
        $userProvider->retrieveByCredentials([]);
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\AppException
     * @expectedExceptionMessage Invalid method
     */
    public function it_throws_exception_on_validateCredentials()
    {
        $userProvider = new DbUserProvider(new DummyUserRepository);
        $userProvider->validateCredentials(new User('provider', 123), []);
    }
}
