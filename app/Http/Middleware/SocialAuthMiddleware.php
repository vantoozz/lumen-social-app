<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundInRepositoryException;
use Closure;
use Illuminate\Http\Request;

/**
 * Class SocialAuthMiddleware
 * @package App\Http\Middleware
 */
class SocialAuthMiddleware
{
    /**
     * @param $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Social\Frame\SocialFrameInterface $social_frame */
        $social_frame = app()->make('App\Social\Frame\SocialFrameInterface');
        $user = $social_frame->getUser($request->query());

        /** @var \App\Repositories\Users\UsersRepositoryInterface $usersRepository */
        $usersRepository = app()->make('App\Repositories\Users\UsersRepositoryInterface');

        try {
            $user = $usersRepository->getByProviderId($user->getProvider(), $user->getProviderId());
        } catch (NotFoundInRepositoryException $e) {
            $user = $usersRepository->create($user);
        }

        /** @var \Illuminate\Auth\Guard $auth */
        $auth = app('auth');
        $user->setLastLoginNow();
        $usersRepository->save($user);
        $auth->login($user);

        return $next($request);
    }
}