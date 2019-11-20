<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class GuzzleServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('guzzle', function () {
            $config = isset($this->app['config']['guzzle']) ? $this->app['config']['guzzle'] : [];
            return new Client($config);
        });
    }
}
