<?php

namespace App\Jobs;

use App\CDN;
use App\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

/**
 * Class UpdateUserCdnPhotoIfNeeded
 * @package App\Jobs
 */
class UpdateUserCdnPhotoIfNeeded implements SelfHandling, ShouldBeQueued
{

    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     *
     */
    public function handle()
    {
        $photo = $this->user->getPhoto();
        if (empty($photo)) {
            $this->updateUserCdnPhotoField(null);

            return;
        }

        $cdn = new CDN();
        $cdn_photo = $cdn->makePath($photo);

        if ($cdn_photo === $this->user->getCdnPhoto()) {
            return;
        }
        $cdn->uploadFromUrl($photo);
        $this->updateUserCdnPhotoField($cdn_photo);
    }

    /**
     * @param $value
     */
    private function updateUserCdnPhotoField($value)
    {
        $stored_value = $this->user->getCdnPhoto();
        if ($stored_value == $value) {
            return;
        }

        $this->user->setCdnPhoto($value);

        /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
        $usersRepository = app('App\Repositories\Users\UsersRepositoryInterface');
        $usersRepository->save($this->user);
    }
}
