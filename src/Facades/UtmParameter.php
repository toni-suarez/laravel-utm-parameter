<?php

namespace Suarez\UtmParameter\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Suarez\UtmParameter\UtmParameter boot(\Illuminate\Http\Request $request)
 * @method static array useRequestOrSession(\Illuminate\Http\Request $request)
 * @method static array all()
 * @method static string|null get(string $key)
 * @method static bool has(string $key, $value = null)
 * @method static bool contains(string $key, string $value)
 * @method static bool clear()
 * @method static array getParameter(\Illuminate\Http\Request $request)
 * @method static string ensureUtmPrefix(string $key)
 *
 * @see \Suarez\UtmParameter\UtmParameter
 */
class UtmParameter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Suarez\UtmParameter\UtmParameter::class;
    }
}
