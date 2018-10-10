# Camvret Laravel Starter Kit

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

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

Install node modules

```bash
npm install
```

Build assets

```bash
npm run prod
```

Add `role` and `permission` middleware to your `app/Http/Kernel.php` file in the `$routeMiddleware` array section.

``` php
protected $routeMiddleware = [
	// ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
];
```

Setup your database configuration then run `migrate` command:

``` bash
php artisan migrate --seed
```

And then run `php artisan serve`. And login with the default user `superadmin@foo.com` and password `secret`

## Usage

For development, you can read the full documentation <a href="https://laravel.com/docs/5.7/frontend">here</a>.
All vue components are in the `resources/assets/js` folder, you can add or modify the file. Enjoy!

## Credits

Thank you to all this beautiful package.

- [CoreUI][link-coreui]
- [Laravel Permission][link-laravel-permission]
- [Laravel UUID][link-laravel-uuid]

## License

MIT. Please see the [license file](license.md) for more information.

<a href="https://www.buymeacoffee.com/daemswibowo" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: auto !important;width: auto !important;" ></a>

[ico-version]: https://img.shields.io/packagist/v/daemswibowo/camvret.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/daemswibowo/camvret.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/daemswibowo/camvret/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/daemswibowo/camvret
[link-downloads]: https://packagist.org/packages/daemswibowo/camvret
[link-travis]: https://travis-ci.org/daemswibowo/camvret
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://daemswibowo.github.io
[link-coreui]: https://coreui.io
[link-laravel-permission]: https://github.com/spatie/laravel-permission
[link-laravel-uuid]: https://github.com/webpatser/laravel-uuid
