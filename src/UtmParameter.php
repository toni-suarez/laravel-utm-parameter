<?php

namespace Suarez\UtmParameter;

use Illuminate\Http\Request;

class UtmParameter
{
    /**
     * Bag containing all UTM-Parameters.
     *
     * @var array
     */
    public $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Bootstrap UtmParameter.
     *
     * @param Request $request
     *
     * @return UtmParameter
     */
    public function boot(Request $request)
    {
        $this->parameters = $this->useRequestOrSession($request);
        return $this;
    }

    /**
     * Check which Parameters should be used.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function useRequestOrSession(Request $request)
    {
        $currentRequestParameter = self::getParameter($request);
        $sessionParameter = session('utm');

        if(!empty($currentRequestParameter) && empty($sessionParameter)) {
            session(['utm' => $currentRequestParameter]);
            return $currentRequestParameter;
        }

        if(!empty($currentRequestParameter) && !empty($sessionParameter) && config('utm-parameter.override_utm_parameters')) {
            $mergedParameters = array_merge($sessionParameter, $currentRequestParameter);
            session(['utm' => $mergedParameters]);
            return $mergedParameters;
        }

        return $sessionParameter;
    }

    /**
     * Retrieve all UTM-Parameter.
     *
     * @return array
     */
    public static function all()
    {
        return app(UtmParameter::class)->parameters ?? [];
    }

    /**
     * Retrieve a UTM-Parameter by key.
     *
     * @param string $key
     *
     * @return string|null
     */
    public static function get($key)
    {
        $parameters = self::all();
        $key = self::ensureUtmPrefix($key);

        if (!array_key_exists($key, $parameters)) {
            return null;
        }

        return $parameters[$key];
    }

    /**
     * Determine if a UTM-Parameter exists.
     *
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public static function has($key, $value = null)
    {
        $parameters = self::all();
        $key = self::ensureUtmPrefix($key);

        if (!array_key_exists($key, $parameters)) {
            return false;
        }

        if (array_key_exists($key, $parameters) && $value !== null) {
            return self::get($key) === $value;
        }

        return true;
    }

    /**
     * Determine if a value contains inside the key.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function contains($key, $value)
    {
        $parameters = self::all();
        $key = self::ensureUtmPrefix($key);

        if (!array_key_exists($key, $parameters) || !is_string($value)) {
            return false;
        }

        return str_contains(self::get($key), $value);
    }

    /**
     * Clear and remove utm session.
     *
     * @return bool
     */
    public static function clear()
    {
        app(UtmParameter::class)->parameters = null;
        session()->forget('utm');
        return true;
    }

    /**
     * Retrieve all UTM-Parameter from the URI.
     *
     * @return array
     */
    protected static function getParameter(Request $request)
    {
        return collect($request->all())
            ->filter(fn ($value, $key) => substr($key, 0, 4) === 'utm_')
            ->map(fn ($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'))
            ->toArray();
    }

    /**
     * Ensure the key to start with 'utm_'.
     *
     * @param string $key
     * @return string
     */
    protected static function ensureUtmPrefix(string $key): string
    {
        return str_starts_with($key, 'utm_') ? $key : 'utm_' . $key;
    }
}
