<?php

namespace App\Listeners;

use App\Activities\UserActivity;
use App\Miners\UserStatus\UserStatusMinerInterface;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Repositories\UserActivity\UserActivityRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderInterface;
use App\Social\Provider\SocialProviderLocator;
use App\TestCase;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use PHPUnit_Framework_Constraint_IsInstanceOf;
use PHPUnit_Framework_MockObject_MockObject;


class SyncUserDataIfNeededTest extends TestCase
{

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $activitiesRepository;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $usersRepository;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $providerFactory;
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $userStatusMiner;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->activitiesRepository = static::getMock(UserActivityRepositoryInterface::class);
        $this->usersRepository = static::getMock(UsersRepositoryInterface::class);
        $this->userStatusMiner = static::getMock(UserStatusMinerInterface::class);

        $this->providerFactory = static::getMockBuilder(SocialProviderLocator::class)
            ->disableOriginalConstructor()
            ->getMock();

    }

    /**
     * @test
     */
    public function it_do_nothing_if_user_is_up_to_date()
    {

        $user = new User;

        $this->userStatusMiner
            ->expects(static::once())
            ->method('isUserInfoOutdated')
            ->with($user)
            ->willReturn(false);

        /** @var UserActivityRepositoryInterface $activitiesRepository */
        $activitiesRepository = $this->activitiesRepository;
        /** @var UsersRepositoryInterface $usersRepository */
        $usersRepository = $this->usersRepository;
        /** @var SocialProviderLocator $providerFactory */
        $providerFactory = $this->providerFactory;
        /** @var UserStatusMinerInterface $userStatusMiner */
        $userStatusMiner = $this->userStatusMiner;

        $listener = new SyncUserDataIfNeeded(
            $activitiesRepository,
            $usersRepository,
            $providerFactory,
            $userStatusMiner
        );

        $event = new Login($user, false);
        $listener->handle($event);
    }

    /**
     * @test
     */
    public function it_updates_outdated_user()
    {

        $provider = static::getMock(SocialProviderInterface::class);

        $user = new User;
        $user->setProvider('some provider');
        $user->setProviderId(12345);

        $providerUser = new User;
        $providerUser->setFirstName('first name');
        $providerUser->setLastName('last name');
        $providerUser->setSex('sex');
        $providerUser->setPhoto('photo');
        $providerUser->setBirthDate(new Carbon('2015-01-01 11:11:11'));

        $this->userStatusMiner
            ->expects(static::once())
            ->method('isUserInfoOutdated')
            ->with($user)
            ->willReturn(true);

        $this->providerFactory
            ->expects(static::once())
            ->method('build')
            ->with('some provider')
            ->willReturn($provider);

        $provider
            ->expects(static::once())
            ->method('getUserByProviderId')
            ->with(12345)
            ->willReturn($providerUser);

        $this->usersRepository
            ->expects(static::once())
            ->method('store')
            ->with($user);

        $this->activitiesRepository
            ->expects(static::once())
            ->method('store')
            ->with(new PHPUnit_Framework_Constraint_IsInstanceOf(UserActivity::class));

        /** @var UserActivityRepositoryInterface $activitiesRepository */
        $activitiesRepository = $this->activitiesRepository;
        /** @var UsersRepositoryInterface $usersRepository */
        $usersRepository = $this->usersRepository;
        /** @var SocialProviderLocator $providerFactory */
        $providerFactory = $this->providerFactory;
        /** @var UserStatusMinerInterface $userStatusMiner */
        $userStatusMiner = $this->userStatusMiner;

        $listener = new SyncUserDataIfNeeded(
            $activitiesRepository,
            $usersRepository,
            $providerFactory,
            $userStatusMiner
        );

        $event = new Login($user, false);
        $listener->handle($event);
    }

}
