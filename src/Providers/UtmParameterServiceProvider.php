<?php

namespace Suarez\UtmParameter\Providers;

use Illuminate\Support\ServiceProvider;
use Suarez\UtmParameter\UtmParameter;

class UtmParameterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UtmParameter::class, function () {
            return new UtmParameter();
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
