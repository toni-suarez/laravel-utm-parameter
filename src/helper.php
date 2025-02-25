<?php

use Suarez\UtmParameter\Facades\UtmParameter;

if (! function_exists('get_all_utm')) {
    function get_all_utm(): array
    {
        return UtmParameter::all();
    }
}

if (! function_exists('get_utm')) {
    function get_utm($key): ?string
    {
        return UtmParameter::get($key);
    }
}

if (! function_exists('has_utm')) {
    function has_utm($key, $value = null): bool
    {
        return UtmParameter::has($key, $value);
    }
}

if (! function_exists('has_not_utm')) {
    function has_not_utm($key, $value = null): bool
    {
        return ! UtmParameter::has($key, $value);
    }
}

if (! function_exists('contains_utm')) {
    function contains_utm($key, $value): bool
    {
        return UtmParameter::contains($key, $value);
    }
}

if (! function_exists('contains_not_utm')) {
    function contains_not_utm($key, $value): bool
    {
        return ! UtmParameter::contains($key, $value);
    }
}
