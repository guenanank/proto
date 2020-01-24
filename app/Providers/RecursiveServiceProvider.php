<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Recursive as RecursiveHelper;

class RecursiveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // dd(app_path() . '/Services/Helpers/Recursive.php');
        // require_once app_path() . '/Services/Helpers/Recursive.php';
        $this->app->singleton('recursive', function () {
            return new RecursiveHelper();
        });
    }
}
