<?php

namespace App\Listeners;

use App\Exceptions\DownloaderException;
use App\Exceptions\RepositoryException;
use App\Media\MediaManager;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UpdateUserCdnPhoto
 * @package App\Listeners
 */
class UpdateUserCdnPhoto implements ShouldQueue
{

    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;
    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * UpdateUserCdnPhoto constructor.
     * @param UsersRepositoryInterface $usersRepository
     * @param MediaManager $mediaManager
     */
    public function __construct(UsersRepositoryInterface $usersRepository, MediaManager $mediaManager)
    {
        $this->usersRepository = $usersRepository;
        $this->mediaManager = $mediaManager;
    }

    /**
     * @param Login $event
     * @throws RepositoryException
     * @throws DownloaderException
     */
    public function handle(Login $event)
    {
        /** @var User $user */
        $user = $event->user;

        $photo = $user->getPhoto();
        if ('' === (string)$photo) {
            $user->setPhoto(null);
            $this->updateUser($user, null);

            return;
        }

        $cdnPhoto = $this->mediaManager->makePath($photo);

        if ($cdnPhoto === $user->getCdnPhoto()) {
            return;
        }
        
        $this->mediaManager->uploadFromUrl($photo);
        $this->updateUser($user, $cdnPhoto);
    }

    /**
     * @param User $user
     * @param $photo
     * @throws RepositoryException
     */
    private function updateUser(User $user, $photo)
    {
        if ($photo === $user->getCdnPhoto()) {
            return;
        }

        $user->setCdnPhoto($photo);

        $this->usersRepository->merge($user);
    }
}
