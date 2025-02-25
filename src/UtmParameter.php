<?php

namespace Suarez\UtmParameter;

use Illuminate\Http\Request;

class UtmParameter
{
    /**
     * Bag containing all UTM-Parameters.
     */
    public ?array $parameters;

    /**
     * Utm Parameter Session Key.
     */
    public string $sessionKey;

    public function __construct(array $parameters = [])
    {
        $this->sessionKey = config('utm-parameter.session_key');
        $this->parameters = $parameters;
    }

    /**
     * Bootstrap UtmParameter.
     */
    public function boot(Request $request): self
    {
        $this->parameters = $this->useRequestOrSession($request);

        return $this;
    }

    /**
     * Check which Parameters should be used.
     */
    public function useRequestOrSession(Request $request): ?array
    {
        $currentRequestParameter = $this->getParameter($request);
        $sessionParameter = session($this->sessionKey);

        if (! empty($currentRequestParameter) && empty($sessionParameter)) {
            session([$this->sessionKey => $currentRequestParameter]);

            return $currentRequestParameter;
        }

        if (! empty($currentRequestParameter) && ! empty($sessionParameter) && config('utm-parameter.override_utm_parameters')) {
            $mergedParameters = array_merge($sessionParameter, $currentRequestParameter);
            session([$this->sessionKey => $mergedParameters]);

            return $mergedParameters;
        }

        return $sessionParameter;
    }

    /**
     * Retrieve all UTM-Parameter.
     */
    public function all(): array
    {
        return session($this->sessionKey) ?? [];
    }

    /**
     * Retrieve a UTM-Parameter by key.
     */
    public function get(string $key): ?string
    {
        $parameters = $this->all();
        $key = $this->ensureUtmPrefix($key);

        if (! array_key_exists($key, $parameters)) {
            return null;
        }

        return $parameters[$key];
    }

    /**
     * Determine if a UTM-Parameter exists.
     *
     * @param  string  $value
     */
    public function has(string $key, $value = null): bool
    {
        $parameters = $this->all();
        $key = $this->ensureUtmPrefix($key);

        if (! array_key_exists($key, $parameters)) {
            return false;
        }

        if (array_key_exists($key, $parameters) && $value !== null) {
            return $this->get($key) === $value;
        }

        return true;
    }

    /**
     * Determine if a value contains inside the key.
     */
    public function contains(string $key, string $value): bool
    {
        $parameters = $this->all();
        $key = $this->ensureUtmPrefix($key);

        if (! array_key_exists($key, $parameters) || ! is_string($value)) {
            return false;
        }

        return str_contains($this->get($key), $value);
    }

    /**
     * Clear and remove utm session.
     */
    public function clear(): bool
    {
        session()->forget($this->sessionKey);
        $this->parameters = null;

        return true;
    }

    /**
     * Retrieve all UTM-Parameter from the URI.
     */
    protected function getParameter(Request $request): array
    {
        $allowedKeys = config('utm-parameter.allowed_utm_parameters', [
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        ]);

        return collect($request->all())
            ->filter(fn ($value, $key) => substr($key, 0, 4) === 'utm_')
            ->filter(fn ($value, $key) => in_array($key, $allowedKeys))
            ->mapWithKeys(fn ($value, $key) => [
                htmlspecialchars($key, ENT_QUOTES, 'UTF-8') => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
            ])
            ->toArray();
    }

    /**
     * Ensure the key to start with 'utm_'.
     */
    protected function ensureUtmPrefix(string $key): string
    {
        return str_starts_with($key, 'utm_') ? $key : 'utm_'.$key;
    }
}
