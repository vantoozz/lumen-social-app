<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use App\Jobs\SyncUserDataIfNeeded;
use Closure;
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
     * @param Request  $request
     * @param callable $next
     * @param string   $provider_name
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $provider_name)
    {
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
}
