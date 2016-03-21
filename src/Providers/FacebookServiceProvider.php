<?php

namespace App\Providers;

use App\Hydrators\User\FacebookUserHydrator;
use App\Social\Provider\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\ServiceProvider;

/**
 * Class FacebookServiceProvider
 * @package App\Providers
 */
class FacebookServiceProvider extends ServiceProvider
{

    const SERVICE_NAME = 'social.fb';

    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @return array
     */
    public function provides()
    {
        return [self::SERVICE_NAME];
    }

    /**
     * Register any application services.
     * @return void
     * @throws FacebookSDKException
     */
    public function register()
    {
        $this->app->singleton(self::SERVICE_NAME, function () {
            return new Facebook(
                new \Facebook\Facebook([
                    'http_client_handler' => 'stream',
                    'app_id' => getenv('FB_APP_ID'),
                    'app_secret' => getenv('FB_APP_SECRET'),
                    'default_graph_version' => getenv('FB_API_VERSION'),
                ]),
                new FacebookUserHydrator
            );
        });
    }
}
