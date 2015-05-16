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
    function () use ($app) {
        /** @var \Illuminate\Auth\Guard $auth */
        $auth = app('auth');
        /** @var App\User $user */
        $user = $auth->user();
        return $user->toArray();
//        return $app->welcome();
    }
);


$app->get(
    '/app/{provider}',
    [
        'middleware' => ['bind_provider', 'social_auth'],
        function () {
            /** @var \Illuminate\Auth\Guard $auth */
            $auth = app('auth');
            /** @var App\User $user */
            $user = $auth->user();
            return $user->toArray();
        }
    ]
);