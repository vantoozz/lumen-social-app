<?php

namespace App\Providers;

use App\Listeners\GetLongLivedAccessToken;
use App\Listeners\LogUserLoginActivity;
use App\Listeners\SyncUserDataIfNeeded;
use App\Listeners\UpdateUserCdnPhoto;
use Illuminate\Auth\Events\Login;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Login::class => [
            LogUserLoginActivity::class,
            SyncUserDataIfNeeded::class,
            UpdateUserCdnPhoto::class,
            GetLongLivedAccessToken::class,
        ],
    ];
}
