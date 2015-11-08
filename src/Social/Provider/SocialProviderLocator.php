<?php

namespace App\Social\Provider;

use App\Exceptions\FactoryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

/**
 * Class SocialProviderLocator
 * @package App\Social\Provider
 */
class SocialProviderLocator
{
    /**
     * @var Container
     */
    private $container;

    /**
     * SocialProviderLocator constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $providerName
     * @return SocialProviderInterface
     * @throws FactoryException
     */
    public function build($providerName)
    {
        try {
            $provider = $this->container->make('social.' . $providerName);
        } catch (BindingResolutionException $exception) {
            throw new FactoryException('No such social provider: ' . $providerName, $exception->getCode(), $exception);
        }
        if (!$provider instanceof SocialProviderInterface) {
            throw new FactoryException('Not a social provider: ' . $providerName);
        }

        return $provider;
    }
}