<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class Handler
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @throws \InvalidArgumentException
     * @return Response
     */
    public function render($request, Exception $e)
    {
        if (true === env('APP_DEBUG')) {
            return $this->renderExceptionWithWhoops($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return Response
     * @throws \InvalidArgumentException
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler());

        return new Response(
            $whoops->handleException($e)
        );
    }

}
