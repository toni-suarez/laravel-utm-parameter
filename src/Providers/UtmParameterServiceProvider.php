<?php

namespace Suarez\UtmParameter\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Suarez\UtmParameter\UtmParameter;

class UtmParameterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UtmParameter::class, fn () => new UtmParameter);
        $this->mergeConfigFrom(__DIR__.'/../config/utm-parameter.php', 'utm-parameter');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/utm-parameter.php' => config_path('utm-parameter.php')], 'utm-parameter');

        Blade::if('hasUtm', function (string $key, ?string $value = null): bool {
            return has_utm($key, $value);
        });

        Blade::if('hasNotUtm', function (string $key, ?string $value = null): bool {
            return has_not_utm($key, $value);
        });

        Blade::if('containsUtm', function (string $key, ?string $value = null): bool {
            return contains_utm($key, $value);
        });

        Blade::if('containsNotUtm', function (string $key, ?string $value = null): bool {
            return contains_not_utm($key, $value);
        });

        AliasLoader::getInstance()->alias('UtmParameter', \Suarez\UtmParameter\Facades\UtmParameter::class);
    }
}
