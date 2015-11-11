<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderInterface;
use App\Social\Provider\SocialProviderLocator;
use App\TestCase;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class SocialAuthMiddlewareTest extends TestCase
{

    /**
     * @test
     */
    public function it_authenticates_a_user()
    {
        $auth = static::getMock(Guard::class);
        $request = static::getMock(Request::class);
        $provider = static::getMock(SocialProviderInterface::class);
        $usersRepository = static::getMock(UsersRepositoryInterface::class);
        $providersLocator =
            static::getMockBuilder(SocialProviderLocator::class)
                ->disableOriginalConstructor()
                ->getMock();

        $user = new User;
        $user->setProvider('provider');
        $user->setProviderId(123);

        $storedUser = new User;
        $storedUser->setProvider('provider');
        $storedUser->setProviderId(123);
        $storedUser->setId(12345);

        $request
            ->expects(static::once())
            ->method('segment')
            ->willReturn('some provider');

        $request
            ->expects(static::once())
            ->method('query')
            ->willReturn(['request data']);

        $providersLocator
            ->expects(static::once())
            ->method('build')
            ->with('some provider')
            ->willReturn($provider);

        $provider
            ->expects(static::once())
            ->method('getFrameUser')
            ->with(['request data'])
            ->willReturn($user);

        $usersRepository
            ->expects(static::once())
            ->method('getByProviderId')
            ->with('provider', 123)
            ->willThrowException(new NotFoundInRepositoryException);

        $usersRepository
            ->expects(static::once())
            ->method('store')
            ->with($user)
            ->willReturn($storedUser);

        $auth
            ->expects(static::once())
            ->method('login')
            ->with($storedUser);


        $next = function ($nextRequest) use ($request) {
            static::assertSame($request, $nextRequest);
        };

        /** @var UsersRepositoryInterface $usersRepository */
        /** @var SocialProviderLocator $providersLocator */
        /** @var Guard $auth */
        /** @var Request $request */
        $middleware = new SocialAuthMiddleware($usersRepository, $providersLocator, $auth);
        $middleware->handle($request, $next);
    }

}