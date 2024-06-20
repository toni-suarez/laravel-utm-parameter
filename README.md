# Laravel UTM-Parameters

[![Latest Version on Packagist](https://img.shields.io/packagist/v/suarez/laravel-utm-parameter.svg?style=flat-square)](https://packagist.org/packages/suarez/laravel-utm-parameter)
[![StyleCI](https://github.styleci.io/repos/448347178/shield?branch=main)](https://github.styleci.io/repos/448347178?branch=main)
[![Test PHP 8.x](https://github.com/toni-suarez/laravel-utm-parameter/actions/workflows/tests-php8.yml/badge.svg?branch=main)](https://github.com/toni-suarez/laravel-utm-parameter/actions/workflows/tests-php8.yml)
[![Packagist Downloads](https://img.shields.io/packagist/dt/suarez/laravel-utm-parameter)](https://packagist.org/packages/suarez/laravel-utm-parameter)
![GitHub](https://img.shields.io/github/license/toni-suarez/laravel-utm-parameter)
[![Statamic Addon](https://img.shields.io/badge/https%3A%2F%2Fstatamic.com%2Faddons%2Ftoni-suarez%2Futm-parameter?style=flat-square&logo=statamic&logoColor=rgb(255%2C%2038%2C%20158)&label=Statamic&link=https%3A%2F%2Fstatamic.com%2Faddons%2Ftoni-suarez%2Futm-parameter)](https://statamic.com/addons/toni-suarez/utm-parameter)


A lightweight way to handle UTM parameters session-based in your Laravel Application.

```blade
@hasUtm('source', 'newsletter')
  <p>Special Content for Newsletter-Subscriber.</p>
@endhasUtm
```

---

## Installation

Follow these steps to install the Laravel UTM-Parameters package. [Guide for Laravel 10 and below.](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Installation-Guide-(Laravel-8.x-to-10.x))

Open your terminal and navigate to your Laravel project directory. Then, use Composer to install the package:

```bash
$ composer require suarez/laravel-utm-parameter
```

### Middleware Configuration

Once the package is installed, you need to add the UtmParameters middleware to your Laravel application. Open the `bootstrap/app.php` file and append the `UtmParameters::class` inside the web-group.

```php
# Laravel 11
return Application::configure(basePath: dirname(__DIR__))
  ...
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
      Suarez\UtmParameter\Middleware\UtmParameters::class,
      /* ... keep the existing middleware here */
    ]);
  })
  ...
```

### Middleware Alias (Optional)

To enable UTM-Parameters only for certain requests or routes in your application, you can add an alias for the UtmParameters middleware. Open the bootstrap/app.php file and append the `UtmParameters::class` inside the web-group.


```php
# Laravel 11
use Suarez\UtmParameter\Middleware\UtmParameters;

->withMiddleware(function (Middleware $middleware) {
    $middleware
        ->alias([
          /* ... keep the existing mappings here */
          'utm-parameters' => UtmParameters::class,
          ])
        ->web(append: [
          /* ... keep the existing mappings here */
          UtmParameters::class
        ]);
})
```

To apply UTM-Parameters to specific routes, use the following middleware: `utm-parameters`

```php
Route::middleware('utm-parameters')
  ->get('landing-page/{slug}', 'LandingPageController@show');
```

## Usage

### get_all_utm()

To get an array of all UTM parameters, use this helper:  `get_all_utm()`.

```php
$parameter = get_all_utm();
```

###  get_utm()

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

### has_utm()

Sometimes you want to show or do something, if user might have some or specific utm-parameters.

Simply use:
- `has_utm('source|medium|campaign|term|content', 'optional-value')`
- `has_not_utm('source|medium|campaign|term|content', 'optional-value')`

```blade
@hasUtm('source', 'corporate-partner')
  <div>Some corporate partner related stuff</div>
@endhasUtm

@hasNotUtm('term')
  <p>You have any term.</p>
@endhasNotUtm
```

```php
 if (has_utm('campaign', 'special-sale')) {
   redirect('to/special-sale/page');
 }

 if (has_not_utm('campaign', 'newsletter')) {
   session()->flash('Did you know, we have a newsletter?');
 }
```


## Resources
Explore additional use cases and resources on the [wiki pages](https://github.com/toni-suarez/laravel-utm-parameter/wiki)

- [Installation Guide](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Installation-Guide)
- [Installation Guide (Laravel 8.x to 10.x)](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Installation-Guide-(Laravel-8.x-to-10.x))
- [How it works](https://github.com/toni-suarez/laravel-utm-parameter/wiki/How-it-works)
- [Limitations](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Limitations)
- [Advanced Usage](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Advanced-Usage)
- [Blade Usage](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Blade-Usage)
- [Usage via Facade or Helper Class](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Usage-via-Facade-or-Helper-Class)

### Inspirations
- [Use Case: A/B Testing](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Use-Case:-A-B-Testing)
- [Use Case: Different Styles for Social Media](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Use-Case:-Different-Styles-for-Social-Media)
- [Use Case: Lead Attribution](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Use-Case:-Lead-Attribution)
- [Use Case: Social Media Tracking](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Use-Case:-Social-Media-Tracking)
- [Use‐Case: Newsletter Redirect on Product Detail Page](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Use%E2%80%90Case:-Newsletter-Redirect-on-Product-Detail-Page)
- [Use‐Case: Offline Marketing Integration](https://github.com/toni-suarez/laravel-utm-parameter/wiki/Use%E2%80%90Case:-Offline-Marketing-Integration)

---

## License

The Laravel UTM-Parameters package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
