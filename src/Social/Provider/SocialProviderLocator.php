<?php declare(strict_types = 1);

namespace App\Social\Provider;

use App\Exceptions\FactoryException;
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
        } catch (\ReflectionException $e) {
            throw new FactoryException('No such social provider: ' . $providerName, $e->getCode(), $e);
        }
        if (!$provider instanceof SocialProviderInterface) {
            throw new FactoryException('Not a social provider: ' . get_class($provider));
        }

        return $provider;
    }
}
