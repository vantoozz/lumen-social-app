<?php

namespace App\Listeners;

use App\Cdn\FilesystemCdn;
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
     * @var FilesystemCdn
     */
    private $cdn;

    /**
     * @param UsersRepositoryInterface $usersRepository
     * @param FilesystemCdn $cdn
     */
    public function __construct(UsersRepositoryInterface $usersRepository, FilesystemCdn $cdn)
    {
        $this->usersRepository = $usersRepository;
        $this->cdn = $cdn;
    }

    /**
     * @param User $user
     */
    public function handle(User $user)
    {
        $photo = $user->getPhoto();
        if ('' === (string)$photo) {
            $this->updateUser($user, null);

            return;
        }

        $cdnPhoto = $this->cdn->makePath($photo);

        if ($cdnPhoto === $user->getCdnPhoto()) {
            return;
        }
        $this->cdn->uploadFromUrl($photo);
        $this->updateUser($user, $cdnPhoto);
    }

    /**
     * @param User $user
     * @param $photo
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