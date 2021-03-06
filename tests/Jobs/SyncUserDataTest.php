<?php declare(strict_types = 1);

namespace App\Jobs;

use App\Activities\UserActivity;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderInterface;
use App\Social\Provider\SocialProviderLocator;
use App\TestCase;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use PHPUnit_Framework_Constraint_IsInstanceOf;

class SyncUserDataTest extends TestCase
{
    /**
     * @test
     */
    public function it_updates_a_user()
    {

        $activitiesRepository = $this->createMock(UserActivityRepositoryInterface::class);
        $usersRepository = $this->createMock(UsersRepositoryInterface::class);
        $providerFactory = $this->getMockBuilder(SocialProviderLocator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $provider = $this->createMock(SocialProviderInterface::class);

        $user = new User('some provider', 12345);

        $providerUser = new User('some provider', 12345);
        $providerUser->setFirstName('first name');
        $providerUser->setLastName('last name');
        $providerUser->setSex('sex');
        $providerUser->setPhoto('photo');
        $providerUser->setBirthDate(new Carbon('2015-01-01 11:11:11'));

        $providerFactory
            ->expects(static::once())
            ->method('build')
            ->with('some provider')
            ->willReturn($provider);

        $provider
            ->expects(static::once())
            ->method('getUserByProviderId')
            ->with(12345)
            ->willReturn($providerUser);

        $usersRepository
            ->expects(static::once())
            ->method('store')
            ->with($user);

        $activitiesRepository
            ->expects(static::once())
            ->method('store')
            ->with(new IsInstanceOf(UserActivity::class));

        /** @var UserActivityRepositoryInterface $activitiesRepository */
        /** @var UsersRepositoryInterface $usersRepository */
        /** @var SocialProviderLocator $providerFactory */

        $job = new SyncUserData($user);
        $job->handle($activitiesRepository, $usersRepository, $providerFactory);
    }
}
