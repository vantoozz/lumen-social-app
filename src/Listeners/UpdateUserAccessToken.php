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
 * Class UpdateUserAccessToken
 * @package App\Listeners
 */
class UpdateUserAccessToken
{

    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;

    /**
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
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

        $accessToken = $user->getAccessToken();
        if ('' === (string)$accessToken) {
            return;
        }

        $this->usersRepository->store($user);
    }
}
