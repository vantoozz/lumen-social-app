<?php

namespace App\Jobs;

use App\TestCase;
use Illuminate\Container\Container;

class JobsLocatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_job()
    {
        $container = new Container;
        $container->singleton('someJob', function () {
            return $this->createMock(JobInterface::class);
        });
        $locator = new JobsLocator($container);

        static::assertInstanceOf(JobInterface::class, $locator->build('someJob', 1, 2, 3));
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\FactoryException
     * @expectedExceptionMessage Cannot build job: someJob
     */
    public function it_throws_exception_if_there_is_no_such_job_in_the_container()
    {
        $container = new Container;
        $locator = new JobsLocator($container);

        static::assertInstanceOf(JobsLocator::class, $locator->build('someJob'));
    }

    /**
     * @test
     * @expectedException     \App\Exceptions\FactoryException
     * @expectedExceptionMessage Not a job: someJob
     */
    public function it_throws_exception_if_target_is_not_a_job()
    {
        $container = new Container;
        $container->singleton('someJob', function () {
            return new \stdClass;
        });
        $locator = new JobsLocator($container);

        static::assertInstanceOf(JobsLocator::class, $locator->build('someJob'));
    }
}
