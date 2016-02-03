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
     * @return JobInterface
     * @throws FactoryException
     */
    public function build($jobName)
    {
        try {
            $job = $this->container->make($jobName);
        } catch (\ReflectionException $e) {
            throw new FactoryException('Cannot build job: ' . $jobName, $e->getCode(), $e);
        }
        if (!$job instanceof JobInterface) {
            throw new FactoryException('Not a job: ' . get_class($job));
        }

        return $job;
    }
}