# Laravel UTM-Parameters

- [Introduction](#introduction)
- [Installation](#installation)
  - [Middleware](#middleware)
- [Usage](#usage)
  - [get_all_utm](#all-utm-parameters)
  - [get_utm](#Certain-utm-parameter)
  - [has_utm|has_not_utm](#determine-utm-parameter)
- [License](#license)

---

## Introduction

A lightweight way to handle UTM-Parameters session-based in your Laravel Application.

*Example*

```blade
@if(has_utm('source', 'newsletter'))
  <p>Special Content for Newsletter-Subscriber.</p>
@endif
```

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

### All UTM-Parameters

To get all UTM-Parameters as an array, you can use `get_all_utm()`.

###  Certain UTM-Parameter

If you wish to retrieve certain UTM-Parameter, you can use ``get_utm('source|medium|campaign|term|content')`.

**For example:**

```blade
 <p>You came from {{ get_utm('source') }}</p>
```

### Determine UTM-Parameter

Sometimes you want to show or do something, if user might have some or specific utm-parameters. Simply use `has_utm('source|medium|campaign|term|content', 'optional-value')` or `has_not_utm('source|medium|campaign|term|content', 'optional-value')`

**For example:**

```blade
 @if(has_utm('term'))
  <p>You have any term.</p>
 @end
```

or

```php
 if (has_utm('campaign', 'special-sale')) {
   redirect('to/special-sale/page');
 }
```

---

## License

The Laravel UTM-Parameters package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
