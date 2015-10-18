<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RoutingException;
use App\Repositories\Users\UsersRepositoryInterface;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\DispatchesJobs;

/**
 * Class SocialAuthMiddleware
 * @package App\Http\Middleware
 */
class SocialAuthMiddleware
{

    use DispatchesJobs;

    /**
     * @var UsersRepositoryInterface
     */
    private $usersRepository;

    /**
     * @var Guard
     */
    private $auth;

    /**
     * SocialAuthMiddleware constructor.
     * @param UsersRepositoryInterface $usersRepository
     * @param Guard $auth
     */
    public function __construct(UsersRepositoryInterface $usersRepository, Guard $auth)
    {
        $this->usersRepository = $usersRepository;
        $this->auth = $auth;
    }


    /**
     * @param $request
     * @param  Closure $next
     * @return mixed
     * @throws RoutingException
     */
    public function handle(Request $request, Closure $next)
    {
        $providerName = $this->getProvider($request);

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = app('social.' . $providerName);
        $user = $provider->getFrameUser($request->query());

        try {
            $user = $this->usersRepository->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $e) {
            $user = $this->usersRepository->create($user);
        }

        $user->setLastLoginNow();
        $this->usersRepository->save($user);

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
        return $request->segment(2);
    }
}
