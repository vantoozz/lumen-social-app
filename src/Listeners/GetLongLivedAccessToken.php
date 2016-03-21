<?php

namespace App\Listeners;

use App\Exceptions\FactoryException;
use App\Exceptions\RepositoryException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Resources\User;
use App\Social\Provider\SocialProviderLocator;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UpdateUserAccessToken
 * @package App\Listeners
 */
class GetLongLivedAccessToken implements ShouldQueue
{
    /**
     * @var SocialProviderLocator
     */
    private $providerLocator;
    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;

    /**
     * GetLongLivedAccessToken constructor.
     * @param SocialProviderLocator $providerLocator
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(SocialProviderLocator $providerLocator, UsersRepositoryInterface $usersRepository)
    {
        $this->providerLocator = $providerLocator;
        $this->usersRepository = $usersRepository;
    }

    /**
     * @param Login $event
     * @throws FactoryException
     * @throws RepositoryException
     */
    public function handle(Login $event)
    {
        /** @var User $user */
        $user = $event->user;

        $accessToken = $user->getAccessToken();
        if ('' === (string)$accessToken) {
            return;
        }

        $providerName = $user->getProvider();
        $provider = $this->providerLocator->build($providerName);

        $accessToken = $provider->getLongLivedAccessToken($user);

        if ($accessToken !== $user->getAccessToken()) {
            $user->setAccessToken($accessToken);
            $this->usersRepository->merge($user);
        }
    }
}
