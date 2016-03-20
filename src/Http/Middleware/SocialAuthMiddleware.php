<?php

namespace App\Http\Middleware;

use App\Exceptions\FactoryException;
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RepositoryException;
use App\Exceptions\RoutingException;
use App\Repositories\Resources\Users\UsersRepositoryInterface;
use App\Social\Provider\SocialProviderLocator;
use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
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
     * @var StatefulGuard
     */
    private $auth;
    /**
     * @var SocialProviderLocator
     */
    private $providersLocator;

    /**
     * SocialAuthMiddleware constructor.
     * @param UsersRepositoryInterface $usersRepository
     * @param StatefulGuard $auth
     * @param SocialProviderLocator $providersLocator
     */
    public function __construct(
        UsersRepositoryInterface $usersRepository,
        SocialProviderLocator $providersLocator,
        StatefulGuard $auth
    ) {

        $this->usersRepository = $usersRepository;
        $this->providersLocator = $providersLocator;
        $this->auth = $auth;
    }


    /**
     * @param $request
     * @param  Closure $next
     * @return mixed
     * @throws RoutingException
     * @throws FactoryException
     * @throws RepositoryException
     */
    public function handle(Request $request, Closure $next)
    {
        $providerName = $this->getProvider($request);

        $provider = $this->providersLocator->build($providerName);
        try {
            $user = $provider->getFrameUser($request->query());
        } catch (NotAuthorizedException $e) {
            return view('fb.auth');
        }

        try {
            $user = $this->usersRepository->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $e) {
            $user = $this->usersRepository->store($user);
        }

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
        return $request->segment(2); // 2 is social provider name in the url (/app/<socialProvider>)
    }
}
