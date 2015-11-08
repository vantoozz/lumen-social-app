<?php

namespace App\Providers;

use App\Listeners\LogUserLoginActivity;
use App\Listeners\UpdateUserCdnPhotoIfNeeded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        'auth.login' => [
            LogUserLoginActivity::class,
//            SyncUserDataIfNeeded::class,
            UpdateUserCdnPhotoIfNeeded::class,
        ],
    ];
}