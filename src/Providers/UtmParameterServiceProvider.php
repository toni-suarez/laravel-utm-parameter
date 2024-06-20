<?php

namespace Suarez\UtmParameter\Providers;

use Illuminate\Support\Facades\Blade;
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
        $this->app->singleton(UtmParameter::class, fn () => new UtmParameter());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('hasUtm', function (string $key, string|null $value = null) {
            return has_utm($key, $value);
        });

        Blade::if('hasNotUtm', function (string $key, string|null $value = null) {
            return has_not_utm($key, $value);
        });
    }
}
