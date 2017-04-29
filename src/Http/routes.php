<?php declare(strict_types = 1);

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get(
    '/',
    function () {
        /** @var App\Resources\User $user */
        $user = app('auth')->user();

        return var_export($user, true);
    }
);


$app->get(
    '/app/{provider}',
    [
        'middleware' => [App\Http\Middleware\SocialAuthMiddleware::class],
        function () {
            /** @var App\Resources\User $user */
            $user = app('auth')->user();
            return var_export($user, true);
        }
    ]
);
