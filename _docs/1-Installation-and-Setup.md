# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [FAQ](4-FAQ.md)
  
## Server Requirements

The Localization package has a few system requirements:

    - PHP >= 5.6

##### Optional

PHP extensions:

  * **ext_intl :** Needed to use the `Locale` class. (http://php.net/manual/en/class.locale.php)

## Version Compatibility

| Localization                           | Laravel                                                                                                             |
|:---------------------------------------|:--------------------------------------------------------------------------------------------------------------------|
| ![Localization v0.x][localization_0_x] | ![Laravel v5.0][laravel_5_0] ![Laravel v5.1][laravel_5_1] ![Laravel v5.2][laravel_5_2] ![Laravel v5.3][laravel_5_3] |
| ![Localization v1.x][localization_1_x] | ![Laravel v5.4][laravel_5_4]                                                                                        |

[laravel_5_0]:  https://img.shields.io/badge/v5.0-supported-brightgreen.svg?style=flat-square "Laravel v5.0"
[laravel_5_1]:  https://img.shields.io/badge/v5.1-supported-brightgreen.svg?style=flat-square "Laravel v5.1"
[laravel_5_2]:  https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_3]:  https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"

[localization_0_x]: https://img.shields.io/badge/version-0.*-blue.svg?style=flat-square "Localization v0.*"
[localization_1_x]: https://img.shields.io/badge/version-1.*-blue.svg?style=flat-square "Localization v1.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: `composer require arcanedev/localization`.

Then install it via `composer install` or `composer update`.

## Laravel

### Setup

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    Arcanedev\Localization\LocalizationServiceProvider::class,
],
```

> No need to register the Localization facade, it's done automagically.

##### Now you need to update your Http Kernel to use the Localization Router.

```php
// app/Http/Kernel.php
<?php namespace App\Http;

//...

class Kernel extends HttpKernel
{
    // Localization Trait
    use \Arcanedev\Localization\Traits\LocalizationKernelTrait;

    // Your middleware(s) here
}
```

### Artisan commands

To publish the config &amp; view files, run this command:

```bash
php artisan vendor:publish --provider="Arcanedev\Localization\LocalizationServiceProvider"
```
