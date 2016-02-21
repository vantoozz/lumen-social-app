<?php

namespace App\Exceptions;

use App\TestCase;

class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_errors_with_whoops()
    {
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
