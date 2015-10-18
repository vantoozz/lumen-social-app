<?php

namespace App\Listeners;


use App\CDN;
use App\Repositories\Users\UsersRepositoryInterface;
use App\User;

class UpdateUserCdnPhotoIfNeeded
{

    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;
    /**
     * @var CDN
     */
    private $cdn;

    /**
     * @param UsersRepositoryInterface $usersRepository
     * @param CDN $cdn
     */
    public function __construct(UsersRepositoryInterface $usersRepository, CDN $cdn)
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
            $this->updateUserCdnPhotoField($user, null);

            return;
        }

        $cdnPhoto = $this->cdn->makePath($photo);

        if ($cdnPhoto === $user->getCdnPhoto()) {
            return;
        }
        $this->cdn->uploadFromUrl($photo);
        $this->updateUserCdnPhotoField($user, $cdnPhoto);
    }

    /**
     * @param User $user
     * @param $photo
     */
    private function updateUserCdnPhotoField(User $user, $photo)
    {
        $stored_value = $user->getCdnPhoto();
        if ($stored_value === $photo) {
            return;
        }

        $user->setCdnPhoto($photo);

        $this->usersRepository->save($user);
    }
}