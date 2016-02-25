<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__ . '/../'))->load();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->configure('auth');
$app->configure('session');
$app->configure('filesystems');

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->register(App\Providers\WhoopsServiceProvider::class);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware(
    [
        // Illuminate\Cookie\Middleware\EncryptCookies::class,
        // 'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
//        Illuminate\Session\Middleware\StartSession::class,
        // 'Illuminate\View\Middleware\ShareErrorsFromSession',
        // 'Laravel\Lumen\Http\Middleware\VerifyCsrfToken',
    ]
);

$app->routeMiddleware(
    [
        App\Http\Middleware\SocialAuthMiddleware::class => App\Http\Middleware\SocialAuthMiddleware::class,
    ]
);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);
$app->register(Illuminate\Session\SessionServiceProvider::class);
$app->register(Illuminate\Redis\RedisServiceProvider::class);
$app->register(App\Providers\DbConnectionServiceProvider::class);
$app->register(App\Providers\DbAuthServiceProvider::class);
$app->register(App\Providers\VKServiceProvider::class);
$app->register(App\Providers\UsersRepositoryServiceProvider::class);
$app->register(App\Providers\EventServiceProvider::class);
$app->register(App\Providers\MediaManagerServiceProvider::class);
$app->register(App\Providers\UserActivityRepositoryServiceProvider::class);
$app->register(App\Providers\UserStatusMinerServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

require __DIR__ . '/../src/Http/routes.php';

return $app;
