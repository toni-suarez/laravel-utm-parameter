<?php

use Suarez\UtmParameter\UtmParameter;

if (!function_exists('get_all_utm')) {
    function get_all_utm()
    {
        return UtmParameter::all();
    }
}

if (!function_exists('get_utm')) {
    function get_utm($key)
    {
        return UtmParameter::get($key);
    }
}

if (!function_exists('has_utm')) {
    function has_utm($key, $value = null)
    {
        return UtmParameter::has($key, $value);
    }
}

if (!function_exists('has_not_utm')) {
    function has_not_utm($key, $value = null)
    {
        return !UtmParameter::has($key, $value);
    }
}
