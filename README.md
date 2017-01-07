# silverleague/silverstripe-logviewer

[![Build Status](https://travis-ci.org/silverleague/silverstripe-logviewer.svg?branch=master)](https://travis-ci.org/silverleague/silverstripe-logviewer) [![Code quality](https://scrutinizer-ci.com/g/silverleague/silverstripe-logviewer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/silverleague/silverstripe-logviewer/?branch=master) [![Code coverage](https://codecov.io/gh/silverleague/silverstripe-logviewer/branch/master/graph/badge.svg)](https://codecov.io/gh/silverleague/silverstripe-logviewer) [![Latest Stable Version](https://poser.pugx.org/silverleague/logviewer/version)](https://packagist.org/packages/silverleague/logviewer) [![Latest Unstable Version](https://poser.pugx.org/silverleague/logviewer/v/unstable)](//packagist.org/packages/silverleague/logviewer)

Show your SilverStripe log entries in the CMS.

## Requirements

* PHP 5.6+
* SilverStripe ^4.0
* Composer

## Installation

Install with composer:

```shell
composer require silverleague/logviewer
```

## Configuration

This is a plug and play module. Simply install, then run a `dev/build` and flush:

```shell
sake dev/build flush=1
```

You will now see a "Jobs" tab in the CMS.

## Support

If you encounter a problem with our module then please let us know by raising an issue on our [issue tracker](https://github.com/silverleague/silverstripe-logviewer/issues).

Ensure you tell us which version of this module you are using, as well as which versions of PHP and SilverStripe framework you are using. If you aren't sure, you can find out by running the following commands from your command line: `php -v`, `composer show silverleague/logviewer` and `composer show silverstripe/framework`.

## Contributing

Please see [the contributing guide](CONTRIBUTING.md) for more information.
