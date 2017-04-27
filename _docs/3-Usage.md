# 3. Usage

## Table of contents

 1. [Installation and Setup](1-Installation-and-Setup.md)
 2. [Configuration](2-Configuration.md)
 3. [Usage](3-Usage.md)
    * [Middleware](#middleware)
    * [Localization](#localization)
    * [Localization Entities](#localization-entities)
    * [Translated Routes](#translated-routes)
    * [Events](#events)
 4. [FAQ](4-FAQ.md)


Localization uses the URL given for the request. In order to achieve this purpose, a route group should be added into the `routes.php` file. It will filter all pages that must be localized.

```php
// app/Http/routes.php
<?php

/* ---------------------------------------------------------
 |  Application Routes
 | ---------------------------------------------------------
 */
Route::localizedGroup(function () {
    // ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP
    Route::get('/', function() {
        return view('home');
    });

    Route::get('test', function() {
        return view('test');
    });
});

// OTHER PAGES THAT SHOULD NOT BE LOCALIZED
```

Once this localized route group is added to the routes file, a user can access all locales added into `supported-locales` (`en`, `es` and `fr` by default, look at the config section to change that option).

For example, a user can now access three different locales, using the following addresses:

```
http://your-project-url
http://your-project-url/en
http://your-project-url/es
http://your-project-url/fr
```

If the locale is not present in the url or it is not defined in `supported-locales`, the system will use the application default locale or the user's browser default locale (if defined in config file).

Once the locale is defined, the locale variable will be stored in a session (if the middleware is enabled), so it is not necessary to write the lang uri section in the url after defining it once, using the last known locale for the user.

If the user accesses to a different locale this session value would be changed, translating any other page he visits with the last chosen locale.

## Middleware

Moreover, this package includes a middleware object to redirect all "non-localized" routes to the corresponding "localized".

So, if a user navigates to `http://your-project-url/test` and the system has this middleware active and `en` as the current locale for this user, it would redirect (301) him automatically to http://your-project-url/en/test. This is mainly used to avoid duplicate content and improve SEO (Search Engine Optimization) performance.

 > Note: All Localization middleware(s) are registered in Localization RoutingServiceProvider, so you don't have to register the middleware(s) in the your `app/Http/Kernel.php`.

By using the `Route::localizedGroup()`, the middleware(s) are automatically inserted in Route attributes based on `config/localization.php` file.

And if you want to do it manually:

```php
// app/Http/routes.php
<?php

Route::group([
    'prefix'     => Localization::setLocale(),
    'middleware' => [
        'localization-session-redirect',
        'localization-redirect',
    ],
], function() {
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', function() {
        return view('hello');
    });

    Route::get('test',function(){
        return view('test');
    });
});

/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
```

In order to activate it, you just have to attach middleware(s) to the routes you want to be accessible localized.

If you want to hide the default locale but always show other locales in the url, switch the `hide-default-in-url` config value to true. Once it's true, if the default locale is en (english) all URLs containing /en/ would be redirected to the same url without this fragment '/' but maintaining the locale as en (English).

**IMPORTANT** - When `hide-default-in-url` is set to true, the unlocalized root is treated as the applications default locale `app.locale`.  Because of this language negotiation using the Accept-Language header will **NEVER** occur when `hide-default-in-url` is true.

## Localization

##### Get URL for an specific locale

```php
/**
 * Returns an URL adapted to $locale or current locale.
 *
 * @param  string|null  $locale
 * @param  string|null  $url
 * @param  array        $attributes
 * @param  bool|false   $showHiddenLocale
 *
 * @return string|false
 */
public function getLocalizedURL($locale = null, $url = null, array $attributes = [], $showHiddenLocale = false)

// OR

/**
 * Returns an URL adapted to $locale or current locale.
 *
 * @param  string       $url
 * @param  string|null  $locale
 *
 * @return string
 */
public function localizeURL($url = null, $locale = null);
```

It returns a localized URL to the desired locale.

##### Get Clean routes

```php
/**
 * It returns an URL without locale (if it has it).
 *
 * @param  string|false  $url
 *
 * @return string
 */
public function getNonLocalizedURL($url = null)
```

It returns a clean URL of any localization.

##### Get URL for an specific translation key

```php
/**
 * Returns an URL adapted to the route name and the locale given.
 *
 * @param  string|bool  $locale
 * @param  string       $transKey
 * @param  array        $attributes
 * @param  bool|false   $showHiddenLocale
 *
 * @return string|false
 */
public function getUrlFromRouteName($locale, $transKey, array $attributes = [], $showHiddenLocale = false)
```

It returns a route, localized to the desired locale using the locale passed.

If the translation key does not exist in the locale given, this function will return `false`.

For a quick use, you can use the `localized_route()` helper:
 
```
/**
 * Get a localized URL with a given trans route name.
 *
 * @param  string       $transRoute
 * @param  array        $attributes
 * @param  string|null  $locale
 *
 * @return string
 */
function localized_route($transRoute, array $attributes = [], $locale = null)
```

##### Get Supported Locales Collection

```php
/**
 * Return an array of all supported Locales.
 *
 * @return \Arcanedev\Localization\Entities\LocaleCollection
 */
public function getSupportedLocales()
```

It returns all locales as a `Arcanedev\Localization\Entities\LocaleCollection` Collection. For more details, check the [LocaleCollection Entity](#localecollection-entity).

##### Get Supported Locales Keys

```php
/**
 * Get supported locales keys.
 *
 * @return array
 */
public function getSupportedLocalesKeys()
```

It returns an array with all the supported locales keys.

##### Set Supported Locales

```php
/**
 * Set the supported locales.
 *
 * @param  array  $supportedLocales
 *
 * @return self
 */
public function setSupportedLocales(array $supportedLocales)
```

Set the localization's supported locales.

##### Get Current Locale

```php
/**
 * Returns current language.
 *
 * @return string
 */
public function getCurrentLocale()
```

It returns the key of the current locale.

##### Get Current Locale Entity

```php
/**
 * Returns current language.
 *
 * @return \Arcanedev\Localization\Entities\Locale
 */
public function getCurrentLocaleEntity()
```

It returns the `Entity` of the current locale. Check the `Arcanedev\Localization\Entities\Locale` class for more details.

##### Get Current Locale Name

```php
/**
 * Returns current locale name.
 *
 * @return string
 */
public function getCurrentLocaleName()
```

It returns the name of the current locale (For example `English`, `Spanish` or `Arabic` ...).

##### Get Current Locale Script

```php
/**
 * Returns current locale script.
 *
 * @return string
 */
public function getCurrentLocaleScript()
```

It returns the [ISO 15924 code](http://www.unicode.org/iso15924/) of the current locale (For example `Latn`, `Cyrl` or `Arab` ...).

##### Get Current Locale Direction

```php
/**
 * Returns current locale direction.
 *
 * @return string
 */
public function getCurrentLocaleDirection()
```

It returns the direction of the current locale: `ltr` (Left to Right) or `rtl` (Right to Left).

##### Get Current Locale Native Language

```php
/**
 * Returns current locale native name.
 *
 * @return string
 */
public function getCurrentLocaleNative()
```

It returns the native name of the current locale.

##### Get Current Locale Regional

```php
/**
 * Returns current locale regional.
 *
 * @return string
 */
public function getCurrentLocaleRegional()
```

It returns the regional of the current locale. The regional locale could be used with the [setlocale()](http://php.net/manual/en/function.setlocale.php) method.

##### Get all locales collection

```php
/**
 * Get all locales.
 *
 * @return \Arcanedev\Localization\Entities\LocaleCollection
 */
public function getAllLocales()
```

It returns all locales as a `Arcanedev\Localization\Entities\LocaleCollection` Collection. For more details, check the [LocaleCollection Entity](#localecollection-entity).

##### Set and return current locale

```php
/**
 * Set and return current locale.
 *
 * @param  string  $locale
 *
 * @return string
 */
public function setLocale($locale = null)
```

This function will change the application's current locale.

If the locale is not passed or `null`, the locale will be determined via a cookie or the session (if stored previously), browser Accept-Language header or the default application locale (depending on your config file).

##### Set the base URL

```php
/**
 * Sets the base url for the site.
 *
 * @param  string  $url
 */
public function setBaseUrl($url)
```

##### Check if given locale is supported

```php
/**
 * Check if Locale exists on the supported locales collection.
 *
 * @param  string|bool  $locale
 *
 * @return bool
 */
public function isLocaleSupported($locale)
```

##### Creating a locales navigation bar

```php
/**
 * Get locales navigation bar.
 *
 * @return string
 */
public function localesNavbar()
```

If you're supporting multiple locales in your project you will probably want to provide the users with a way to change language.

**Note :** You can publish and modify the blade template markups.

The `localesNavbar` function would work as desired and it will translate the routes to all translated languages.

 > *Note: Don't forget to add any new route to the translation file.*

**IMPORTANT: You may have an issue with `localesNavbar` method if you're using the Route bindings, See [Issue  #19](https://github.com/ARCANEDEV/Localization/issues/19) for more details.**

If you're using some route bindings by using `$router->bind()` or `$router->model()`.

You need to implement the `Arcanedev\Localization\Contracts\RouteBindable` interface to your binded class to render the correct wildcard values.

For example:

```php
<?php namespace App;

// Other use statements...
use Arcanedev\Localization\Contracts\RouteBindable;

class User
    extends Model
    implements AuthenticatableContract,
               AuthorizableContract,
               CanResetPasswordContract,
               RouteBindable
{
    //...

    /**
     * Get the wildcard value from the class.
     *
     * @return int|string
     */
    public function getWildcardValue()
    {
        return $this->id; // You can return whatever you want (username, hashed id ...)
    }

    //...
}
```

## Localization Entities

##### LocaleCollection Entity

The `Arcanedev\Localization\Entities\LocaleCollection` class extends from `Illuminate\Support\Collection`, so it provides a fluent, convenient wrapper for working with locales data (`Locale` entities).

For more details, check the [Illuminate\Support\Collection documentation](http://laravel.com/docs/5.1/collections).

##### Locale Entity

The `Locale` Entity implements the `Illuminate\Contracts\Support\Arrayable`, `Illuminate\Contracts\Support\Jsonable` and `JsonSerializable` to simplify the conversion.

The available methods:

```php
/**
 * Get local key.
 *
 * @return string
 */
public function key()
```

```php
/**
 * Get locale name.
 *
 * @return string
 */
public function name()
```

```php
/**
 * Get locale Script.
 *
 * @return string
 */
public function script()
```

```php
/**
 * Get locale direction.
 *
 * @return string
 */
public function direction()
```

```php
/**
 * Get locale native.
 *
 * @return string
 */
public function native()
```

```php
/**
 * Get locale regional.
 *
 * @return string
 */
public function regional()
```

```php
/**
 * Check if it is a default locale.
 *
 * @return bool
 */
public function isDefault()
```

```php
/**
 * Create Locale instance.
 *
 * @param  string  $key
 * @param  array   $data
 *
 * @return self
 */
public static function make($key, array $data)
```

## Translated Routes

You can adapt your URLs depending on the language you want to show them. For example:

```
http://your-project-url/en/about
http://your-project-url/es/acerca    (acerca is about in Spanish)
http://your-project-url/fr/a-propos  (a-propos is about in French)
```

Or with wildcards :

```
http://your-project-url/en/view/5
http://your-project-url/es/ver/5       (view == ver in Spanish)
http://your-project-url/fr/afficher/5  (view == afficher in French)
```

This would be redirected to the same controller using the proper middleware and setting up the translation files as follows :

```php
// config/localization.php
<?php

return [
    //...

    /* ------------------------------------------------------------------------------------------------
     |  Route
     | ------------------------------------------------------------------------------------------------
     */
    'route'                  => [
        'middleware' => [
            'localization-session-redirect' => true,  // Optional
            'localization-cookie-redirect'  => false, // Optional
            'localization-redirect'         => true,  // Optional
            'localized-routes'              => true,  // Required to be true
            'translation-redirect'          => false, // Optional
        ],
    ],

    //...
];
```

Routes :

```php
// app/Http/routes.php
<?php

/* ------------------------------------------------------------------------------------------------
 |  Application Routes
 | ------------------------------------------------------------------------------------------------
 */
Route::localizedGroup(function () {
    Route::get('/', function () {
        return view('home');
    });

    Route::transGet('routes.about', function () {
        return view('about');
    });

    Route::transGet('routes.view', function ($id) {
        return view('page', compact('id'));
    });
});
```

In the routes file you just have to enable the `localized-routes` middleware and the `Route::transGet` function to every route you want to translate using the translation key.

 > Note: *Route::transGet* is a translated get method, *Route::transPost* for the post method and so on.

Then you have to create the translation files and add there every key you want to translate. I suggest to create a `routes.php` file inside your `resources/lang/{locale}` folder.

For the previous example, I have created three translations files, these three files would look like:

```php
// resources/lang/en/routes.php
<?php

return [
    'about' => 'about',
    'view'  => 'view/{id}', // we add a route parameter
    // other translated routes
];

```

```php
// resources/lang/es/routes.php
<?php

return [
    'about' => 'acerca',
    'view'  => 'ver/{id}', // we add a route parameter
    // other translated routes
];

```

```php
// resources/lang/fr/routes.php
<?php

return [
    'about' => 'a-propos',
    'view'  => 'afficher/{id}', // we add a route parameter
    // other translated routes
];

```

Once files are saved, you can access to these urls without any problem :

```
http://your-project-url/en/about
http://your-project-url/es/acerca
http://your-project-url/fr/a-propos
```

Or:

```
http://your-project-url/en/view/5
http://your-project-url/es/ver/5
http://your-project-url/fr/afficher/5
```

## Events

You can capture the URL parameters during translation if you wish to translate them too.

To do so, just create an event listener for the `routes.translation` event like so:

```php
Event::listen('routes.translation', function ($locale, $route, $attributes) {
    // You can store these translation in you database
    // or using the laravel's localization folders
    $translations = [
        'view'  => [
            'en'    => [
                'slug'  => 'hello',
            ],
            'es'    => [
                'slug'  => 'hola',
            ],
            'fr'    => [
                'slug'  => 'salut',
            ],
        ],
    ];

    // This is just a dummy thing to fetch and merge the translation.
    if (
        isset($translations[$route]) && isset($translations[$route][$locale])
    ) {
        $attributes = array_merge($attributes, $translations[$route][$locale]);
    }

    return $attributes;
});
```

Be sure to pass `$locale` and `$route` and `$attributes` as parameters to the closure and you should return a translated `$attributes`.

And also make sure that `localized-routes` and `translation-redirect` are enabled.

```php
// config/localization.php
<?php

return [
    //...

    /* ------------------------------------------------------------------------------------------------
     |  Route
     | ------------------------------------------------------------------------------------------------
     */
    'route'                  => [
        'middleware' => [
            'localization-session-redirect' => true,  // Optional
            'localization-cookie-redirect'  => false, // Optional
            'localization-redirect'         => true,  // Optional
            'localized-routes'              => true,  // Required to be true
            'translation-redirect'          => true,  // Required to be true
        ],
    ],

    //...
];
```

You may also use [Event Subscribers](http://laravel.com/docs/5.1/events#event-subscribers).
