<?php

namespace CampaigningBureau\UnbounceApiClient;

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
        $this->publishes([
            __DIR__ . '/../config/unbounce.php' => config_path('unbounce.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UnbounceApiClient::class, function () {
            return new UnbounceApiClient(new Client());
        });
        $this->app->alias(UnbounceApiClient::class, 'unbounce-api-client');
    }
}
