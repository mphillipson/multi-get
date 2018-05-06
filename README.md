# Multi-GET
This is a Laravel package containing a simplified version of a "download booster", which speeds up downloads by requesting
files in multiple pieces simultaneously (saturating the network), then reassembling the pieces.

This project was developed for [Carnegie Technologies](https://www.carnegietechnologies.com) as a [coding exercise](http://dist.pravala.com/coding/CarnegieCodingCheckMultiGet.pdf) for Software Engineering candidates.

### Features
While I structured the core functionality of this package with an eye toward versatility, I was mindful of the fact that the exercise parameters explicitly prescribe a command-line interface.

My solution: a custom [Artisan Command](https://laravel.com/docs/5.4/artisan).

In addition to meeting the exercise requirements, I opted to implement all of the optional features cited in the [exercise instructions](http://dist.pravala.com/coding/CarnegieCodingCheckMultiGet.pdf), including:
- **Parallel (asynchronous) file download**
- **Support for files smaller (or larger) than 4 MiB**
- **Configurable number of chunks/chunk size/total download size**

## Installation
This package is designed to be loaded into an existing **Laravel 5.4+** project using **Composer**. However, given that this was "only" a coding exercise, I did not take the additional step of registering it in **Packagist**.

It is therefore necessary to run the following command from your Terminal to add my repository to your `composer.json` file before you install the package:
```
$ composer config repositories.mphillipson vcs https://github.com/mphillipson/multi-get
```

Then run this command to add the package as a dependency:
```
$ composer require mphillipson/multi-get:dev-master
```

Don't forget to register the package service provider in your `config/app.php` file:
```php
'providers' => [
    ...
    MPhillipson\Multiget\Providers\MultigetServiceProvider::class,
];
```

And finally, run the following command to publish the package config file to `config/multiget.php` (optional):
```
php artisan vendor:publish
```

## Configuration
You can customize the property values in the published `config/multiget.php` file in order to adjust the default values for the target file path, number of chunks to download, chunk size, and more.

Many of these configuration options can be overriden via Artisan command options, as described below.

## Usage
### `multiget:download`

This Artisan command can be used to download part of a file from a URL to a default location (`/tmp`):
```
php artisan multiget:download [url]
```

To specify a destination other than the default, include the `--target-file` option:
```
php artisan multiget:download [url] --target-file=[/path/to/file]
```

Consult the command help to display and describe all of the available options for the `multiget:download` command:
```
php artisan help multiget:download
```