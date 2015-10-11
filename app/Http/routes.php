<?php

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
        /** @var App\User $user */
        $user = app('auth')->user();

        return var_export($user, true);
    }
);


$app->get(
    '/app/{provider}',
    [
        'middleware' => ['social_auth'],
        function () {
            /** @var App\User $user */
            $user = app('auth')->user();

            return $user->toArray();
        }
    ]
);
