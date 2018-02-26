<?php

namespace CampaigningBureau\UnbounceApiClient;

use CampaigningBureau\UnbounceApiClient\Authorization\ApiKeyAuthorizationDriver;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // publish config-files
        $this->publishes(
            [
                __DIR__ . '/../config/unbounce.php' => config_path('unbounce.php'),
            ]
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $authorizationDriver = new ApiKeyAuthorizationDriver(config('unbounce.api_key'));

        $this->app->singleton(
            UnbounceApiClient::class,
            function () use ($authorizationDriver)
            {
                return new UnbounceApiClient(new Client(), $authorizationDriver);
            }
        );
        $this->app->alias(UnbounceApiClient::class, 'unbounce-api-client');
    }
}
