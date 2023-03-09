# Laravel UTM-Parameters

[![StyleCI](https://github.styleci.io/repos/448347178/shield?branch=main)](https://github.styleci.io/repos/448347178?branch=main)
[![Test PHP 8.x](https://github.com/toni-suarez/laravel-utm-parameter/actions/workflows/tests-php8.yml/badge.svg?branch=main)](https://github.com/toni-suarez/laravel-utm-parameter/actions/workflows/tests-php8.yml)
[![Packagist Downloads](https://img.shields.io/packagist/dt/suarez/laravel-utm-parameter)](https://packagist.org/packages/suarez/laravel-utm-parameter)
![GitHub](https://img.shields.io/github/license/toni-suarez/laravel-utm-parameter)


A lightweight way to handle UTM parameters session-based in your Laravel Application.

```blade
@hasUtm('source', 'newsletter')
  <p>Special Content for Newsletter-Subscriber.</p>
@endhasUtm
```

---

## Table of Content

- [Introduction](#introduction)
- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Introduction

What are these UTM parameters? UTM is an acronym standing for "Urchin Tracking Module" and where initially introduced in Google Analytics. It's a way, mostly marketers track effectiveness of online marketing campaigns.

There are five different UTM parameters:
- utm_source
- utm_medium
- utm_campaign
- utm_content
- utm_term

Not all parameters are used everytime.
Here would be a common example: https://www.example.com/?utm_source=newsletter&utm_medium=email&utm_campaign=holiday-sale


## Installation

Install the `utm-parameter` package with composer:

```
$ composer require suarez/laravel-utm-parameter
```

### Middleware

Open the `app/Http/Kernel.php` file and add a new item to the `web` middleware group:

```php
protected $middlewareGroups = [
    'web' => [
        /* ... keep the existing middleware here */
        \Suarez\UtmParameter\Middleware\UtmParameters::class,
    ],
];
```

To enable UTM-Parameters only for certain requests to your site, add a new mapping to either the `routeMiddleware` (Laravel 9) or the `middlewareAliases` (Laravel 10) Array.

```php
# Laravel 9 and below
protected $routeMiddleware = [
    /* ... keep the existing mappings here */
    'utm-parameters' => \Suarez\UtmParameter\Middleware\UtmParameters::class,
];

# Laravel 10
protected $middlewareAliases = [
    /* ... keep the existing mappings here */
    'utm-parameters' => \Suarez\UtmParameter\Middleware\UtmParameters::class,
];
```

To apply UTM-Parameters to specific routes, use the following middleware: `utm-parameters`

```php
Route::middleware('utm-parameters')
  ->get('langing-page/{slug}', 'LandingPageController@show');
```

## Usage

### All UTM parameters

To get an array of all UTM parameters, use this helper:  `get_all_utm()`.

```php
$parameter = get_all_utm();
```

###  Get UTM parameter

If you need to retrieve certain UTM parameters, use `get_utm('source|medium|campaign|term|content')`.

```blade
 <p>You came from {{ get_utm('source') }}</p>
```

```php

// Some Task in your Class
public function someTask()
{
  return match(get_utm('source')) {
    'bing' => Bing::class,
    'google' => Google::class,
    'duckduckgo' => DuckDuckGo::class,
    'newsletter' => Newsletter::class,
    default => Default::class
  };
}

// Render a view based on an utm_source
Route::get('/', function () {
  return match(get_utm('source')) {
        'newsletter' => view('newsletter'),
        default => view('welcome')
    };
});
```

### Has UTM parameter

Sometimes you want to show or do something, if user might have some or specific utm-parameters.

Simply use:
- `has_utm('source|medium|campaign|term|content', 'optional-value')`
- `has_not_utm('source|medium|campaign|term|content', 'optional-value')`

```blade
 @hasUtm('term')
  <p>You have any term.</p>
 @endhasUtm

 @hasUtm('source', 'corporate-partner')
  <div>Some corporate partner related stuff</div>
 @endhasUtm
```

```php
 if (has_utm('campaign', 'special-sale')) {
   redirect('to/special-sale/page');
 }

 if (has_not_utm('campaign', 'newsletter')) {
   session()->flash('Did you know, we have a newsletter?');
 }
```

---

## License

The Laravel UTM-Parameters package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
