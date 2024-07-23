# InvisibleDragon shared basis for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/invisibledragon/laravel-baseplate.svg?style=flat-square)](https://packagist.org/packages/invisibledragon/laravel-baseplate)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/invisibledragon/laravel-baseplate/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/invisibledragon/laravel-baseplate/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/invisibledragon/laravel-baseplate/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/invisibledragon/laravel-baseplate/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/invisibledragon/laravel-baseplate.svg?style=flat-square)](https://packagist.org/packages/invisibledragon/laravel-baseplate)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require invisibledragon/laravel-baseplate
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-baseplate-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-baseplate-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-baseplate-views"
```

## Usage

```php
$laravelBaseplate = new InvisibleDragon\LaravelBaseplate();
echo $laravelBaseplate->echoPhrase('Hello, InvisibleDragon!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Joe Simpson](https://github.com/kennydude)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
