<?php

namespace App\Jobs;

use App\Exceptions\FactoryException;
use Illuminate\Contracts\Container\Container;

/**
 * Class JobsLocator
 * @package App\Jobs
 */
class JobsLocator
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
     * @param $jobName
     * @param array $parameters
     * @return JobInterface
     * @throws FactoryException
     */
    public function build($jobName, ...$parameters)
    {
        try {
            $job = $this->container->make($jobName, $parameters);
        } catch (\ReflectionException $e) {
            throw new FactoryException('Cannot build job: ' . $jobName, $e->getCode(), $e);
        }
        if (!$job instanceof JobInterface) {
            throw new FactoryException('Not a job: ' . $jobName);
        }

        return $job;
    }
}
