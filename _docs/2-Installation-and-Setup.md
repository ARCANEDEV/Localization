# 2. Installation

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: `composer require arcanedev/localization`.

Or by adding the package to your `composer.json`.

```json
{
    "require": {
        "arcanedev/localization": "0.8.*"
    }
}
```

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

### Artisan commands

To publish the config &amp; view files, run this command:

```bash
php artisan vendor:publish --provider="Arcanedev\Localization\LocalizationServiceProvider"
```
