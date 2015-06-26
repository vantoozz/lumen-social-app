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

        return  $user ? $user->toArray() : new \Illuminate\Http\Response('yep&');
    }
);


$app->get(
    '/app/vk',
    [
        'middleware' => ['social_auth:vk'],
        function () {
            /** @var App\User $user */
            $user = app('auth')->user();

            return $user->toArray();
        }
    ]
);
