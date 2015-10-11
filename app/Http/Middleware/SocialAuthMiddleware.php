<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use App\Exceptions\RoutingException;
use App\Jobs\SyncUserDataIfNeeded;
use App\Repositories\Users\UsersRepositoryInterface;
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
     * @var UsersRepositoryInterface
     */
    private $usersRepository;

    /**
     * SocialAuthMiddleware constructor.
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(UsersRepositoryInterface $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }


    /**
     * @param $request
     * @param  Closure $next
     * @return mixed
     * @throws RoutingException
     */
    public function handle(Request $request, Closure $next)
    {
        $provider_name = $this->getProvider($request);

        /** @var \App\Social\Provider\SocialProviderInterface $provider */
        $provider = app('social.' . $provider_name);
        $user = $provider->getFrameUser($request->query());

        try {
            $user = $this->usersRepository->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $e) {
            $user = $this->usersRepository->create($user);
        }

        $user->setLastLoginNow();
        $this->usersRepository->save($user);

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
