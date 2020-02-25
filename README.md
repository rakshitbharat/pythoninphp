# Run Python Script from PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rakshitbharat/pythoninphp.svg?style=flat-square)](https://packagist.org/packages/rakshitbharat/pythoninphp)
[![Build Status](https://img.shields.io/travis/rakshitbharat/pythoninphp/master.svg?style=flat-square)](https://travis-ci.org/rakshitbharat/pythoninphp)
[![Quality Score](https://img.shields.io/scrutinizer/g/rakshitbharat/pythoninphp.svg?style=flat-square)](https://scrutinizer-ci.com/g/rakshitbharat/pythoninphp)
[![Total Downloads](https://img.shields.io/packagist/dt/rakshitbharat/pythoninphp.svg?style=flat-square)](https://packagist.org/packages/rakshitbharat/pythoninphp)

This package can give you helper functions to run python files right from PHP.

## Installation

You can install the package via composer:

```bash
composer require rakshitbharat/pythoninphp
```

## Usage

``` php
// Simply run python script in PHP by using 
Rakshitbharat\Pythoninphp\RunRun::thisFile('File path from Laravel Root')

// For example
Rakshitbharat\Pythoninphp\RunRun::thisFile('app/test.py')
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email rakshitbharatproject@gmail.com instead of using the issue tracker.

## Credits

- [Rakshit Patel](https://github.com/rakshitbharat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
