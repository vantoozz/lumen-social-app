<?php

namespace App\Http\Middleware;

use App\Exceptions\FactoryException;
use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RoutingException;
use App\Repositories\Users\UsersRepositoryInterface;
use App\Social\Provider\SocialProviderFactory;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class SocialAuthMiddleware
 * @package App\Http\Middleware
 */
class SocialAuthMiddleware
{
    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;
    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var SocialProviderFactory
     */
    private $providerFactory;

    /**
     * SocialAuthMiddleware constructor.
     * @param UsersRepositoryInterface $usersRepository
     * @param Guard $auth
     * @param SocialProviderFactory $providerFactory
     */
    public function __construct(
        UsersRepositoryInterface $usersRepository,
        SocialProviderFactory $providerFactory,
        Guard $auth
    ) {
        $this->usersRepository = $usersRepository;
        $this->providerFactory = $providerFactory;
        $this->auth = $auth;
    }


    /**
     * @param $request
     * @param  Closure $next
     * @return mixed
     * @throws RoutingException
     * @throws FactoryException
     */
    public function handle(Request $request, Closure $next)
    {
        $providerName = $this->getProvider($request);

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = $this->providerFactory->build($providerName);
        $user = $provider->getFrameUser($request->query());

        try {
            $user = $this->usersRepository->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $exception) {
            $user = $this->usersRepository->store($user);
        }

        $user->setLastLoginNow();
        $this->usersRepository->store($user);

        $this->auth->login($user);

        return $next($request);
    }

    /**
     * @param  Request $request
     * @return string
     * @throws RoutingException
     */
    private function getProvider(Request $request)
    {
        return $request->segment(2); // 2 is social provider name in the url (/auth/<socialProvider>)
    }
}
