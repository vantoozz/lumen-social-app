<?php

namespace App\Exceptions;

use App\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Whoops\Run;

class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_errors_with_whoops()
    {
        $debug = getenv('APP_DEBUG');
        $this->setDebugVariable('true');
        $whoops = static::getMock(Run::class);
        $exception = new \Exception('some exception message');
        $whoops
            ->expects(static::once())
            ->method('handleException')
            ->with($exception)
            ->willReturn('some string');
        /** @var Run $whoops */
        $handler = new Handler($whoops);
        $request = new Request;
        $response = $handler->render($request, $exception);
        static::assertInstanceOf(Response::class, $response);
        /** @var Response $response */
        static::assertSame('some string', $response->getContent());
        $this->setDebugVariable($debug);
    }

    /**
     * @test
     */
    public function it_renders_errors_with_no_whoops()
    {
        $debug = getenv('APP_DEBUG');
        $this->setDebugVariable('false');

        $whoops = static::getMock(Run::class);
        $exception = new \Exception('some exception message');
        $whoops->expects(static::never())->method('handleException');
        /** @var Run $whoops */
        $handler = new Handler($whoops);
        $request = new Request;
        $response = $handler->render($request, $exception);
        static::assertInstanceOf(Response::class, $response);
        /** @var Response $response */
        static::assertContains('Whoops, looks like something went wrong.', $response->getContent());

        $this->setDebugVariable($debug);
    }

    /**
     * @param $debug
     */
    protected function setDebugVariable($debug)
    {
        putenv('APP_DEBUG=' . $debug);
        $_ENV['APP_DEBUG'] = $debug;
        $_SERVER['APP_DEBUG'] = $debug;
    }

}
