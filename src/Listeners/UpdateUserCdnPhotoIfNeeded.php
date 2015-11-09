<?php

namespace App\Listeners;

use App\Exceptions\DownloaderException;
use App\Exceptions\RepositoryException;
use App\Media\MediaManager;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UpdateUserCdnPhotoIfNeeded
 * @package App\Listeners
 */
class UpdateUserCdnPhotoIfNeeded implements ShouldQueue
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
     * UpdateUserCdnPhotoIfNeeded constructor.
     * @param UsersRepositoryInterface $usersRepository
     * @param MediaManager $mediaManager
     */
    public function __construct(UsersRepositoryInterface $usersRepository, MediaManager $mediaManager)
    {
        $this->usersRepository = $usersRepository;
        $this->mediaManager = $mediaManager;
    }

    /**
     * @param User $user
     * @throws RepositoryException
     * @throws DownloaderException
     */
    public function handle(User $user)
    {
        $photo = $user->getPhoto();
        if ('' === (string)$photo) {
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

        $this->usersRepository->store($user);
    }
}
