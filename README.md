# Jetstream Rise is an enhanced version of Laravel Jetstream, featuring built-in support for multiple front-end frameworks like React and Svelte. It’s designed to elevate your development experience with flexibility, scalability, and modern UI options, while still retaining the simplicity of Laravel Jetstream.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hichemtab-tech/jetstream-rise.svg?style=flat-square)](https://packagist.org/packages/hichemtab-tech/jetstream-rise)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hichemtab-tech/jetstream-rise/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hichemtab-tech/jetstream-rise/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hichemtab-tech/jetstream-rise/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hichemtab-tech/jetstream-rise/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hichemtab-tech/jetstream-rise.svg?style=flat-square)](https://packagist.org/packages/hichemtab-tech/jetstream-rise)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/jetstream-rise.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/jetstream-rise)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require hichemtab-tech/jetstream-rise
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="jetstream-rise-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="jetstream-rise-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="jetstream-rise-views"
```

## Usage

```php
$jetstreamRise = new HichemTabTech\JetstreamRise();
echo $jetstreamRise->echoPhrase('Hello, HichemTabTech!');
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

- [HichemTab](https://github.com/hichemtab-tech)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
