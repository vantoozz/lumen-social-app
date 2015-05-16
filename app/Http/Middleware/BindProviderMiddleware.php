<?php

namespace App\Http\Middleware;

use App\Exceptions\RoutingException;
use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Novanova\VK\VK;

/**
 * Class BindProviderMiddleware
 * @package App\Http\Middleware
 */
class BindProviderMiddleware
{
    /**
     * @param $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $provider = $this->getProvider($request);
        $this->bindProviderInterface($provider);
        return $next($request);
    }

    /**
     * @param Request $request
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

    /**
     * @param $provider
     */
    private function bindProviderInterface($provider)
    {
        switch ($provider) {
            case 'vk':
                app()->singleton(
                    'App\Social\Frame\SocialFrameInterface',
                    function () {
                        return new \App\Social\Frame\VK(new VK(getenv('VK_APP_ID'), getenv('VK_SECRET')));
                    }
                );
                break;
            default:
                throw new InvalidArgumentException('No such provider');
        }
    }

}