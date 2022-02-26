# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [FAQ](4-FAQ.md)

## Version Compatibility

| Laravel                      | Localization                           |
|:-----------------------------|:---------------------------------------|
| ![Laravel v9.*][laravel_9_x] | ![Localization v9.x][localization_9_x] |
| ![Laravel v8.*][laravel_8_x] | ![Localization v8.x][localization_8_x] |
| ![Laravel v7.*][laravel_7_x] | ![Localization v7.x][localization_7_x] |
| ![Laravel v6.*][laravel_6_x] | ![Localization v6.x][localization_6_x] |
| ![Laravel v5.8][laravel_5_8] | ![Localization v5.x][localization_5_x] |
| ![Laravel v5.7][laravel_5_7] | ![Localization v4.x][localization_4_x] |
| ![Laravel v5.6][laravel_5_6] | ![Localization v3.x][localization_3_x] |
| ![Laravel v5.5][laravel_5_5] | ![Localization v2.x][localization_2_x] |
| ![Laravel v5.4][laravel_5_4] | ![Localization v1.x][localization_1_x] |
| ![Laravel v5.3][laravel_5_3] | ![Localization v0.x][localization_0_x] |
| ![Laravel v5.2][laravel_5_2] | ![Localization v0.x][localization_0_x] |
| ![Laravel v5.1][laravel_5_1] | ![Localization v0.x][localization_0_x] |
| ![Laravel v5.0][laravel_5_0] | ![Localization v0.x][localization_0_x] |

[laravel_9_x]:  https://img.shields.io/badge/version-9.x-blue.svg?style=flat-square "Laravel v9.*"
[laravel_8_x]:  https://img.shields.io/badge/version-8.x-blue.svg?style=flat-square "Laravel v8.*"
[laravel_7_x]:  https://img.shields.io/badge/version-7.x-blue.svg?style=flat-square "Laravel v7.*"
[laravel_6_x]:  https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "Laravel v6.*"
[laravel_5_8]:  https://img.shields.io/badge/version-5.8-blue.svg?style=flat-square "Laravel v5.8"
[laravel_5_7]:  https://img.shields.io/badge/version-5.7-blue.svg?style=flat-square "Laravel v5.7"
[laravel_5_6]:  https://img.shields.io/badge/version-5.6-blue.svg?style=flat-square "Laravel v5.6"
[laravel_5_5]:  https://img.shields.io/badge/version-5.5-blue.svg?style=flat-square "Laravel v5.5"
[laravel_5_4]:  https://img.shields.io/badge/version-5.4-blue.svg?style=flat-square "Laravel v5.4"
[laravel_5_3]:  https://img.shields.io/badge/version-5.3-blue.svg?style=flat-square "Laravel v5.3"
[laravel_5_2]:  https://img.shields.io/badge/version-5.2-blue.svg?style=flat-square "Laravel v5.2"
[laravel_5_1]:  https://img.shields.io/badge/version-5.1-blue.svg?style=flat-square "Laravel v5.1"
[laravel_5_0]:  https://img.shields.io/badge/version-5.0-blue.svg?style=flat-square "Laravel v5.0"

[localization_9_x]: https://img.shields.io/badge/version-9.*-blue.svg?style=flat-square "Localization v9.*"
[localization_8_x]: https://img.shields.io/badge/version-8.*-blue.svg?style=flat-square "Localization v8.*"
[localization_7_x]: https://img.shields.io/badge/version-7.*-blue.svg?style=flat-square "Localization v7.*"
[localization_6_x]: https://img.shields.io/badge/version-6.*-blue.svg?style=flat-square "Localization v6.*"
[localization_5_x]: https://img.shields.io/badge/version-5.*-blue.svg?style=flat-square "Localization v5.*"
[localization_4_x]: https://img.shields.io/badge/version-4.*-blue.svg?style=flat-square "Localization v4.*"
[localization_3_x]: https://img.shields.io/badge/version-3.*-blue.svg?style=flat-square "Localization v3.*"
[localization_2_x]: https://img.shields.io/badge/version-2.*-blue.svg?style=flat-square "Localization v2.*"
[localization_1_x]: https://img.shields.io/badge/version-1.*-blue.svg?style=flat-square "Localization v1.*"
[localization_0_x]: https://img.shields.io/badge/version-0.*-blue.svg?style=flat-square "Localization v0.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: 

```bash
composer require arcanedev/localization
```

PHP extensions:

  * **ext_intl :** Needed to use the `Locale` class. (http://php.net/manual/en/class.locale.php)

## Laravel

### Setup

> **NOTE :** The package will automatically register itself if you're using Laravel `>= v5.5`, so you can skip this section.

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    Arcanedev\Localization\LocalizationServiceProvider::class,
],
```

> No need to register the Localization facade, it's done automagically.

### Artisan commands

To publish the config &amp; view files, run this command:

```bash
php artisan vendor:publish --provider="Arcanedev\Localization\LocalizationServiceProvider"
```
