<?php

namespace App\Providers;

use Http\Client\HttpClient;
use Illuminate\Support\ServiceProvider;
use Smalot\Cups\Transport\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HttpClient::class, Client::class);
    }
}
