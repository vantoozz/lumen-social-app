<?php

namespace App\Listeners;

use App\Media\MediaManager;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use App\TestCase;
use Illuminate\Auth\Events\Login;

class UpdateUserCdnPhotoTest extends TestCase
{

    /**
     * @test
     */
    public function it_updates_user_cdn_photo()
    {
        $usersRepository = static::getMock(UsersRepositoryInterface::class);
        $mediaManager = static::getMockBuilder(MediaManager::class)->disableOriginalConstructor()->getMock();

        $mediaManager
            ->expects(static::once())
            ->method('makePath')
            ->with('http://example.com/photo.jpg')
            ->willReturn('new_path.jpg');

        $mediaManager
            ->expects(static::once())
            ->method('uploadFromUrl')
            ->with('http://example.com/photo.jpg')
            ->willReturn('new_path.jpg');

        $userToStore = new User('some provider', 123);
        $userToStore->setPhoto('http://example.com/photo.jpg');
        $userToStore->setCdnPhoto('new_path.jpg');

        $usersRepository
            ->expects(static::once())
            ->method('store')
            ->with($userToStore);

        $user = new User('some provider', 123);
        $user->setPhoto('http://example.com/photo.jpg');
        $user->setCdnPhoto('old_path.jpg');

        /** @var UsersRepositoryInterface $usersRepository */
        /** @var MediaManager $mediaManager */
        $listener = new UpdateUserCdnPhoto($usersRepository, $mediaManager);

        $event = new Login($user, false);
        $listener->handle($event);
    }

    /**
     * @test
     */
    public function it_updates_user_cdn_photo_once()
    {
        $usersRepository = static::getMock(UsersRepositoryInterface::class);
        $mediaManager = static::getMockBuilder(MediaManager::class)->disableOriginalConstructor()->getMock();

        $mediaManager
            ->expects(static::once())
            ->method('makePath')
            ->with('http://example.com/photo.jpg')
            ->willReturn('old_path.jpg');

        $mediaManager->expects(static::never())->method('uploadFromUrl');

        $usersRepository->expects(static::never())->method('store');

        $user = new User('some provider', 123);
        $user->setPhoto('http://example.com/photo.jpg');
        $user->setCdnPhoto('old_path.jpg');

        /** @var UsersRepositoryInterface $usersRepository */
        /** @var MediaManager $mediaManager */
        $listener = new UpdateUserCdnPhoto($usersRepository, $mediaManager);

        $event = new Login($user, false);
        $listener->handle($event);
    }

    /**
     * @test
     */
    public function it_removes_user_cdn_photo()
    {
        $usersRepository = static::getMock(UsersRepositoryInterface::class);
        $mediaManager = static::getMockBuilder(MediaManager::class)->disableOriginalConstructor()->getMock();

        $mediaManager->expects(static::never())->method('makePath');

        $mediaManager->expects(static::never())->method('uploadFromUrl');

        $userToStore = new User('some provider', 123);
        $userToStore->setPhoto('');
        $userToStore->setCdnPhoto(null);

        $usersRepository
            ->expects(static::once())
            ->method('store')
            ->with($userToStore);

        $user = new User('some provider', 123);
        $user->setPhoto('');
        $user->setCdnPhoto('old_path.jpg');

        /** @var UsersRepositoryInterface $usersRepository */
        /** @var MediaManager $mediaManager */
        $listener = new UpdateUserCdnPhoto($usersRepository, $mediaManager);

        $event = new Login($user, false);
        $listener->handle($event);
    }

    /**
     * @test
     */
    public function it_removes_user_cdn_photo_once()
    {
        $usersRepository = static::getMock(UsersRepositoryInterface::class);
        $mediaManager = static::getMockBuilder(MediaManager::class)->disableOriginalConstructor()->getMock();

        $mediaManager->expects(static::never())->method('makePath');
        $mediaManager->expects(static::never())->method('uploadFromUrl');
        $usersRepository->expects(static::never())->method('store');

        $user = new User('some provider', 123);
        $user->setPhoto('');
        $user->setCdnPhoto(null);

        /** @var UsersRepositoryInterface $usersRepository */
        /** @var MediaManager $mediaManager */
        $listener = new UpdateUserCdnPhoto($usersRepository, $mediaManager);

        $event = new Login($user, false);
        $listener->handle($event);
    }
}
