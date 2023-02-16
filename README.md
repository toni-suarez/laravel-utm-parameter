# Laravel UTM-Parameters

[![StyleCI](https://github.styleci.io/repos/448347178/shield?branch=main)](https://github.styleci.io/repos/448347178?branch=main)
[![Test PHP 8.x](https://github.com/toni-suarez/laravel-utm-parameter/actions/workflows/tests-php8.yml/badge.svg?branch=main)](https://github.com/toni-suarez/laravel-utm-parameter/actions/workflows/tests-php8.yml)
[![Packagist Downloads](https://img.shields.io/packagist/dt/suarez/laravel-utm-parameter)](https://packagist.org/packages/suarez/laravel-utm-parameter)
![GitHub](https://img.shields.io/github/license/toni-suarez/laravel-utm-parameter)


A lightweight way to handle UTM parameters session-based in your Laravel Application.

```blade
@if(has_utm('source', 'newsletter'))
  <p>Special Content for Newsletter-Subscriber.</p>
@endif
```

---

## Table of Content

- [Introduction](#introduction)
- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Introduction

What are these UTM parameters? UTM is an acronym standing for "Urchin Tracking Module" and where initially introduced in Google Analytics. It's a way, mostly marketers track effectiveness of online marketing compaings.

There are five different UTM parameters:
- utm_source
- utm_medium
- utm_campaign
- utm_content
- utm_term

Not all parameters are used everytime.
Here would be a common example: www.example.com/?utm_source=newsletter&utm_medium=email&utm_campaign=holiday-sale


## Installation

Install the `utm-parameter` package with composer:

```
$ composer require suarez/laravel-utm-parameter
```

### Middleware

Open `app/Http/Kernel.php` and add a new item to the `web` middleware group:

```php
protected $middlewareGroups = [
    'web' => [
        /* ... keep the existing middleware here */
        \Suarez\UtmParameter\Middleware\UtmParameters::class,
    ],
];
```

If you want to selectively make UTM-Parameters available on specific requests to your site, you should instead add a new mapping to the `routeMiddleware` array:

```php
protected $routeMiddleware = [
    /* ... keep the existing mappings here */
    'utm-parameters' => \Suarez\UtmParameter\Middleware\UtmParameters::class,
];
```

To make UTM-Parameters available for certain given requests, use the `utm-parameters` middleware:

```php
Route::middleware('utm-parameters')->get('langing-page/{slug}', 'LandingPageController@show');
```

## Usage

### All UTM parameters

To retrieve all UTM parameters as an array, you could use the helper `get_all_utm()`.

```php
$parameter = get_all_utm();
```

###  Get UTM parameter

If you need to retrieve certain UTM parameter, you can use `get_utm('source|medium|campaign|term|content')`.

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
 @if(has_utm('term'))
  <p>You have any term.</p>
 @end
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
