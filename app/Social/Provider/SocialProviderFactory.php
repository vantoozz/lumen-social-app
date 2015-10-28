<?php

namespace App\Social\Provider;


use App\Exceptions\FactoryException;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class SocialProviderFactory
 * @package App\Social\Provider
 */
class SocialProviderFactory
{

    /**
     * @param $providerName
     * @return SocialProviderInterface
     * @throws FactoryException
     */
    public function build($providerName)
    {
        try {
            $provider = app('social.' . $providerName);
        } catch (BindingResolutionException $exception) {
            throw new FactoryException('No such social provider: ' . $providerName, $exception->getCode(), $exception);
        }
        if (!$provider instanceof SocialProviderInterface) {
            throw new FactoryException('Not a social provider: ' . $providerName);
        }

        return $provider;
    }
}