<?php

namespace App\Listeners;

use App\Activities\UserActivity;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use App\TestCase;
use Illuminate\Auth\Events\Login;

class LogUserLoginActivityTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_login_activity()
    {
        $repository = static::getMock(UserActivityRepositoryInterface::class);

        $repository
            ->expects(static::once())
            ->method('store')
            ->with(
                static::logicalAnd(
                    static::isInstanceOf(UserActivity::class),
                    static::attributeEqualTo('userId', 123)
                )
            );

        $user = new User;
        $user->setId(123);

        /** @var UserActivityRepositoryInterface $repository */
        $listener = new LogUserLoginActivity($repository);

        $event  = new Login($user, false);
        $listener->handle($event);
    }
}
