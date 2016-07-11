<?php

namespace App\Exceptions;

use App\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Whoops\Run;
use Whoops\Util\SystemFacade;

class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_errors_with_whoops()
    {
        $debug = getenv('APP_DEBUG');
        $this->setDebugVariable('true');
        $systemFacade = $this->createMock(SystemFacade::class);
        $exception = new \Exception('some exception message');
        $systemFacade
            ->expects(static::once())
            ->method('cleanOutputBuffer')
            ->willReturn('some string');
        $whoops = new Run($systemFacade);
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

        $systemFacade = $this->createMock(SystemFacade::class);
        $exception = new \Exception('some exception message');
        $systemFacade
            ->expects(static::never())
            ->method('cleanOutputBuffer');
        $whoops = new Run($systemFacade);
        /** @var Run $whoops */
        $handler = new Handler($whoops);
        $request = new Request;
        $response = $handler->render($request, $exception);
        static::assertInstanceOf(Response::class, $response);
        /** @var Response $response */
        static::assertContains('Whoops, looks like something went wrong', $response->getContent());

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
