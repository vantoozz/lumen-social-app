<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderInterface;
use App\Social\Provider\SocialProviderLocator;
use App\TestCase;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;

class SocialAuthMiddlewareTest extends TestCase
{

    /**
     * @test
     */
    public function it_authenticates_a_user()
    {
        $auth = static::getMock(StatefulGuard::class);
        $request = static::getMock(Request::class);
        $provider = static::getMock(SocialProviderInterface::class);
        $usersRepository = static::getMock(UsersRepositoryInterface::class);
        $providersLocator =
            static::getMockBuilder(SocialProviderLocator::class)
                ->disableOriginalConstructor()
                ->getMock();

        $user = new User('provider', 123);

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
            ->method('merge')
            ->with($user);

        $auth
            ->expects(static::once())
            ->method('login')
            ->with($user);


        $next = function ($nextRequest) use ($request) {
            static::assertSame($request, $nextRequest);
        };

        /** @var UsersRepositoryInterface $usersRepository */
        /** @var SocialProviderLocator $providersLocator */
        /** @var StatefulGuard $auth */
        /** @var Request $request */
        $middleware = new SocialAuthMiddleware($usersRepository, $providersLocator, $auth);
        $middleware->handle($request, $next);
    }
}
