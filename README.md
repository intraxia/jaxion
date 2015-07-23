# Jaxion

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Jaxion is a WordPress plugin framework for simplifying the use of a common set of object-oriented development patterns. 

## Install

Via Composer

``` bash
$ composer require intraxia/jaxion
```

## Usage

Extend `Intraxia\Jaxion\Application`, define your Services as closures in the constructor, then run `App::boot()`.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email jamesorodig@gmail.com instead of using the issue tracker.

## Credits

- [James DiGioia][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/intraxia/jaxion.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/intraxia/jaxion/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/intraxia/jaxion.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/intraxia/jaxion.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/intraxia/jaxion.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/intraxia/jaxion
[link-travis]: https://travis-ci.org/intraxia/jaxion
[link-scrutinizer]: https://scrutinizer-ci.com/g/intraxia/jaxion/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/intraxia/jaxion
[link-downloads]: https://packagist.org/packages/intraxia/jaxion
[link-author]: https://github.com/mAAdhaTTah
