# Superbolt Symfony

[![Latest Version on Packagist][ico-version]][link-packagist]
[![GitHub Workflow Status][ico-actions]][link-actions]
[![Total Downloads][ico-downloads]][link-downloads]

Debugging background tasks is already hard enough as it is. Superbolt monitors your scheduled tasks and saves the logs for you.

This package integrates access to the Superbolt API with your Symfony (4 or 5) application.

## Installation

Via Composer

``` bash
$ composer require superbolt/superbolt-symfony
```

## Usage

Superbolt will listen to your commands out of the box. All you need to do is create a `config/packages/superbolt.yaml` and fill in the right values:

```yaml
superbolt:
  environment: '%env(APP_ENV)%'
  secret: '%env(SB_SECRET)%'
```

The example above references variables you may define in the `.env` file, as this is a best practice.

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email info@superbolt.app instead of using the issue tracker.

## Credits

- [Superbolt team][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/superbolt/superbolt-symfony.svg?style=flat-square
[ico-actions]: https://img.shields.io/github/workflow/status/superboltapp/superbolt-symfony/Run%20tests/master?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/superbolt/superbolt-symfony.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/superbolt/superbolt-symfony
[link-actions]: https://github.com/superboltapp/superbolt-symfony/actions
[link-downloads]: https://packagist.org/packages/superbolt/superbolt-symfony
[link-author]: https://github.com/superboltapp
[link-contributors]: ../../contributors
