<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RoutingException;
use App\Jobs\SyncUserDataIfNeeded;
use Closure;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\DispatchesCommands;

/**
 * Class SocialAuthMiddleware
 * @package App\Http\Middleware
 */
class SocialAuthMiddleware
{

    use DispatchesCommands;

    /**
     * @param $request
     * @param  callable $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $provider_name = $this->getProvider($request);

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = app('social.' . $provider_name);
        $user = $provider->getFrameUser($request->query());

        /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
        $usersRepository = app('App\Repositories\Users\UsersRepositoryInterface');

        try {
            $user = $usersRepository->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $e) {
            $user = $usersRepository->create($user);
        }

        $user->setLastLoginNow();
        $usersRepository->save($user);

        /** @var \Illuminate\Auth\Guard $auth */
        $auth = app('auth');
        $auth->login($user);

        $job = new SyncUserDataIfNeeded($user);
        $this->dispatch($job);

        return $next($request);
    }

    /**
     * @param  Request $request
     * @return string
     * @throws RoutingException
     */
    private function getProvider(Request $request)
    {
        $route = $request->route();
        if (empty($route[2]) or empty($route[2]['provider'])) {
            throw new RoutingException('Cannot resolve provider');
        }

        return (string)$route[2]['provider'];
    }
}
