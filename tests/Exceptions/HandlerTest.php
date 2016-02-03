<?php

namespace App\Exceptions;

use App\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ReflectionClass;
use Whoops\Run;

class HandlerTest extends TestCase
{

    private function checkIfWhoopsRunIsFinal()
    {
        $run = new ReflectionClass(Run::class);
        if ($run->isFinal()) {
//            static::markTestSkipped('Whoops\\Run is still final. See https://github.com/filp/whoops/pull/364');
        }
    }

    /**
     * @test
     */
    public function it_renders_errors_with_whoops()
    {
        $this->checkIfWhoopsRunIsFinal();
        $debug = getenv('APP_DEBUG');
        $this->setDebugVariable('true');

        static::assertContains('Whoops, looks like something went wrong.', $this->get('/asd')->response->content());

        $this->setDebugVariable($debug);
    }

    /**
     * @test
     */
    public function it_renders_errors_with_no_whoops()
    {
        $this->checkIfWhoopsRunIsFinal();
        $debug = getenv('APP_DEBUG');
        $this->setDebugVariable('false');

        static::assertContains(
            'Sorry, the page you are looking for could not be found.',
            $this->get('/asd')->response->content()
        );

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
