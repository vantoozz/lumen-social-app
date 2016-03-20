<?php

$app->get(
    '/',
    function () {
        /** @var App\Resources\User $user */
        $user = app('auth')->user();

        return var_export($user, true);
    }
);


$app->get('/app/{provider}', 'App\Http\Controllers\CanvasController@index');
$app->post('/app/{provider}', 'App\Http\Controllers\CanvasController@index');
