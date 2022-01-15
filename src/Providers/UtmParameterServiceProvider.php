<?php

namespace Suarez\UtmParameter\Providers;

use Suarez\UtmParameter\UtmParameter;
use Illuminate\Support\ServiceProvider;

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
