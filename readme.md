# Camvret Laravel Starter Kit

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
<!-- [![StyleCI][ico-styleci]][link-styleci] -->

This is a Laravel starter kit using VueJs and Core UI (Bootstrap Free Admin Template). Only support for Laravel ^5.5.

## Installation

You can install the package via composer


``` bash
composer require daemswibowo/camvret
```

In Laravel 5.5 the service provider will automatically get registered. Now just run this command via terminal

``` bash
php artisan camvret:install
```
This command will publish all the important resources for you (The default file will replaced).

Add `role` and `permission` middleware to your `app/Http/Kernel.php` file in the `$routeMiddleware` array section.

``` php
protected $routeMiddleware = [
	// ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
];
```

## Usage



## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` php
protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
];
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email daemswibowo@gmail.com instead of using the issue tracker.

## Credits

- [Dimas Wibowo][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/daemswibowo/camvret.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/daemswibowo/camvret.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/daemswibowo/camvret/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/daemswibowo/camvret
[link-downloads]: https://packagist.org/packages/daemswibowo/camvret
[link-travis]: https://travis-ci.org/daemswibowo/camvret
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/daemswibowo
[link-contributors]: ../../contributors]