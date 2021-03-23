<?php

namespace Apility\Bring\Providers;

use Apility\Bring\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class BringServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bring.client', function () {
            return new Client([
                'login_id' => Config::get('bring.login_id', env('BRING_LOGIN_ID')),
                'api_key' => Config::get('bring.api_key', env('BRING_API_KEY'))
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
