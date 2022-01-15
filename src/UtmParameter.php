<?php

namespace Suarez\UtmParameter;

class UtmParameter
{
    /**
     * Bag containing all UTM-Parameters.
     *
     * @var array
     */
    public $parameters;

    public function __construct($parameters = [])
    {
        $this->parameters = is_string($parameters)
            ? json_decode($parameters, true)
            : $parameters;
    }

    /**
     * Bootstrap UtmParameter.
     *
     * @param array|null $parameters
     * @return UtmParameter
     */
    public function boot($parameters = null)
    {
        if (!$parameters) {
            $parameters = self::getParameter();
            session(['utm' => $parameters]);
        }

        $this->parameters = is_string($parameters)
            ? json_decode($parameters, true)
            : $parameters;

        return app(UtmParameter::class, $parameters);
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
     * @return string|null
     */
    public static function get($key)
    {
        $parameters = self::all();

        if (strpos($key, 'utm_') === false) {
            $key = 'utm_' . $key;
        }

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
     * @return bool
     */
    public static function has($key, $value = null)
    {
        $parameters = self::all();

        if (strpos($key, 'utm_') === false) {
            $key = 'utm_' . $key;
        }

        if (!array_key_exists($key, $parameters)) {
            return false;
        }

        if (array_key_exists($key, $parameters) && $value !== null) {
            return self::get($key) === $value;
        }

        return true;
    }

    /**
     * Retrieve all UTM-Parameter from the URI.
     *
     * @return array
     */
    protected static function getParameter()
    {
        return collect(request()->all())
            ->filter(fn ($value, $key) => substr($key, 0, 4) === "utm_")
            ->map(fn ($value) => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'))
            ->toArray();
    }
}
