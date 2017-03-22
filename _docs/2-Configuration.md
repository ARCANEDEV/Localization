# 2. Configuration

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  4. [FAQ](4-FAQ.md)
  
## Settings

```php
// config/localization.php

<?php

return [
    'supported-locales'      => ['en', 'es', 'fr'],

    'accept-language-header' => true,

    'hide-default-in-url'    => false,

    'facade'                 => 'Localization',

    //...
```

| Name                   | Description                                                                                      |
| ---------------------- | ------------------------------------------------------------------------------------------------ |
| supported-locales      | Your supported locales. (*DUH !*)                                                                |
| accept-language-header | Using the locale negotiator to get locale from `Accept-Language` header.                         |
| hide-default-in-url    | If `true`, the non localized root is treated as the application's default locale (`app.locale`). |
| facade                 | The alias name for the facade.                                                                   |

## Route

```php
// config/localization.php
<?php

return [
    // ...

    'route'                  => [
        'middleware' => [
            'localization-session-redirect' => true,
            'localization-cookie-redirect'  => false,
            'localization-redirect'         => true,
            'localized-routes'              => true,
            'translation-redirect'          => true,
        ]
    ],

    // ...
```

| Name                          | Description                                                              |
| ----------------------------- | ------------------------------------------------------------------------ |
| localization-session-redirect | Redirect all "non-localized" to the "localized" URLs by using sessions.  |
| localization-cookie-redirect  | Redirect all "non-localized" to the "localized" URLs by using cookies.   |
| localization-redirect         | Redirect all "non-localized" to the "localized" URLs.                    |
| localized-routes              | Allows to register all translatable routes.                              |
| translation-redirect          | Allows to translate the route attributes by using the translation event. |

## Ignored URI

```php
    'ignored-uri' => [
        //
    ],
```

You can set a list of uris to ignore from localization checks.

## Locales

```php
// config/localization.php
<?php

return [
    // ...

    'locales'   => [
        // A
        //====================================================>
        'aa'         => [
            'name'   => 'Afar',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Qafar',
        ],

        // ...

        'zu'         => [
            'name'   => 'Zulu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'IsiZulu',
        ],
    ],
];
```
