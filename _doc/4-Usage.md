# 4. Usage

## Table of contents

* [Middleware](#middleware)
* [Helpers](#helpers)
  * [Localization (Facade or helper function)](#localization-facade-or-helper-function)
* [Localization Entities](#localization-entities)
* [Translated Routes](#translated-routes)
* [Events](#events)


Localization uses the URL given for the request. In order to achieve this purpose, a route group should be added into the `routes.php` file. It will filter all pages that must be localized.

```php
// app/Http/routes.php
<?php

/* ---------------------------------------------------------
 |  Application Routes
 | ---------------------------------------------------------
 */
Route::localizedGroup(function () {
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::get('/', function() {
        return view('home');
    });

    Route::get('test', function() {
        return view('test');
    });
});

/** OTHER PAGES THAT SHOULD NOT BE LOCALIZED **/
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

## Helpers

This package comes with some useful functions, like:

### Localization (Facade or helper function)

##### Get URL for an specific locale

```php
/**
 * Returns an URL adapted to $locale or current locale.
 *
 *
 * @param  string|bool   $locale
 * @param  string|false  $url
 * @param  array         $attributes
 *
 * @return string|false
 *
 * @throws UndefinedSupportedLocalesException
 * @throws UnsupportedLocaleException
 */
public function getLocalizedURL($locale = null, $url = null, $attributes = [])

// OR

/**
 * Returns an URL adapted to $locale or current locale.
 *
 * @param  string       $url
 * @param  string|null  $locale
 *
 * @throws UnsupportedLocaleException
 *
 * @return string
 */
public function localizeURL($url = null, $locale = null);
```

(Description & Example here)

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

(Description & Example here)

##### Get URL for an specific translation key

```php
/**
 * Returns an URL adapted to the route name and the locale given.
 *
 * @param  string|bool  $locale
 * @param  string       $transKey
 * @param  array        $attributes
 *
 * @return string|false
 *
 * @throws UndefinedSupportedLocalesException
 * @throws UnsupportedLocaleException
 */
public function getUrlFromRouteName($locale, $transKey, $attributes = [])
```

(Description & Example here)

##### Get Supported Locales Collection

```php
/**
 * Return an array of all supported Locales.
 *
 * @return \Arcanedev\Localization\Entities\LocaleCollection
 *
 * @throws UndefinedSupportedLocalesException
 */
public function getSupportedLocales()
```

(Description & Example here)

##### Get Supported Locales Keys

```php
/**
 * Get supported locales keys.
 *
 * @return array
 *
 * @throws UndefinedSupportedLocalesException
 */
public function getSupportedLocalesKeys()
```

(Description & Example here)

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

(Description & Example here)

##### Get Current Locale

```php
/**
 * Returns current language.
 *
 * @return string
 */
public function getCurrentLocale()
```

(Description & Example here)

##### Get Current Locale Entity

```php
/**
 * Returns current language.
 *
 * @return \Arcanedev\Localization\Entities\Locale
 */
public function getCurrentLocaleEntity()
```

(Description & Example here)

##### Get Current Locale Name

```php
/**
 * Returns current locale name.
 *
 * @return string
 */
public function getCurrentLocaleName()
```

(Description & Example here)

##### Get Current Locale Script

```php
/**
 * Returns current locale script.
 *
 * @return string
 */
public function getCurrentLocaleScript()
```

(Description & Example here)

##### Get Current Locale Direction

```php
/**
 * Returns current locale direction.
 *
 * @return string
 */
public function getCurrentLocaleDirection()
```

(Description & Example here)

##### Get Current Locale Native Language

```php
/**
 * Returns current locale native name.
 *
 * @return string
 */
public function getCurrentLocaleNative()
```

(Description & Example here)

##### Get all locales collection

```php
/**
 * Get all locales.
 *
 * @return \Arcanedev\Localization\Entities\LocaleCollection
 */
public function getAllLocales()
```

(Description & Example here)

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

(Description & Example here)

##### Set the base URL

```php
/**
 * Sets the base url for the site.
 *
 * @param  string  $url
 */
public function setBaseUrl($url)
```

(Description & Example here)

##### Check if given locale is supported

```php
/**
 * Check if Locale exists on the supported locales collection.
 *
 * @param  string|bool  $locale
 *
 * @throws UndefinedSupportedLocalesException
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

(Description & Example here)

## Localization Entities

##### LocaleCollection Entity

The `Arcanedev\Localization\Entities\LocaleCollection` class extends from `Illuminate\Support\Collection`, so it provides a fluent, convenient wrapper for working with locales data (Locale entities).

For more details, check the [Collection documentation](http://laravel.com/docs/5.1/collections).

(More details & Examples here)

##### Locale Entity

(Description & Examples here)

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
            'localization-session-redirect' => false, // or true
            'localization-cookie-redirect'  => false, // or true
            'localization-redirect'         => true,
            'localized-routes'              => true,  // Required to be true
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

The `localesNavbar` function would work as desired and it will translate the routes to all translated languages.

 > *Note: Don't forget to add any new route to the translation file.* 

## Events

You can capture the URL parameters during translation if you wish to translate them too. 

To do so, just create an event listener for the `routes.translation` event like so:

```php
Event::listen('routes.translation', function ($locale, $attributes, $route) {
    /**
     * @var  string                     $locale
     * @var  array                      $attributes
     * @var  \Illuminate\Routing\Route  $route
     */

    // Translate your attributes.

    return $attributes;
});
```

Be sure to pass `$locale` and `$attributes` as parameters to the closure (`$route` is optional) and you should return a translated `$attributes`. 

You may also use [Event Subscribers](http://laravel.com/docs/5.1/events#event-subscribers).
