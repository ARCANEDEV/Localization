<?php

return [
    /* ------------------------------------------------------------------------------------------------
     |  Settings
     | ------------------------------------------------------------------------------------------------
     */
    'supported-locales'      => ['en', 'es', 'fr'],

    'accept-language-header' => true,

    'hide-default-in-url'    => false,

    'facade'                 => 'Localization',

    /* ------------------------------------------------------------------------------------------------
     |  Route
     | ------------------------------------------------------------------------------------------------
     */
    'route'                  => [
        'middleware' => [
            'localization-session-redirect' => true,
            'localization-cookie-redirect'  => false,
            'localization-redirect'         => true,
            'localized-routes'              => true,
        ],
    ],

    /* ------------------------------------------------------------------------------------------------
     |  Locales
     | ------------------------------------------------------------------------------------------------
     */
    'locales'   => [
        // A
        //====================================================>
        'aa'         => [
            'name'   => 'Afar',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Qafar',
        ],
        'ab'         => [
            'name'   => 'Abkhazian',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Аҧсуа',
        ],
        'ace'        => [
            'name'   => 'Achinese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Aceh',
        ],
        'ady'        => [
            'name'   => 'Adyghe',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Адыгэбзэ',
        ],
        'ae'         => [
            'name'   => 'Avestan',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Avesta',
        ],
        'af'         => [
            'name'   => 'Afrikaans',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Afrikaans',
        ],
        'agq'        => [
            'name'   => 'Aghem',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Aghem',
        ],
        'ak'         => [
            'name'   => 'Akan',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Akan',
        ],
        'ale'        => [
            'name'   => 'Aleut',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Unangax tunuu',
        ],
        'am'         => [
            'name'   => 'Amharic',
            'script' => 'Ethi',
            'dir'    => 'ltr',
            'native' => 'አማርኛ',
        ],
        'an'         => [
            'name'   => 'Aragonese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Aragonés',
        ],
        'ang'        => [
            'name'   => 'Old English',
            'script' => 'Runr',
            'dir'    => 'ltr',
            'native' => 'Old English',
        ],
        'ar'         => [
            'name'   => 'Arabic',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'العربية',
        ],
        'as'         => [
            'name'   => 'Assamese',
            'script' => 'Beng',
            'dir'    => 'ltr',
            'native' => 'অসমীয়া',
        ],
        'asa'        => [
            'name'   => 'Kipare',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kipare',
        ],
        'av'         => [
            'name'   => 'Avaric',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Авар мацӀ',
        ],
        'ay'         => [
            'name'   => 'Aymara',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Aymar aru',
        ],
        'az'         => [
            'name'   => 'Azerbaijani (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Azərbaycanca',
        ],
        'az-Cyrl'    => [
            'name'   => 'Azerbaijani (Cyrillic)',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Азәрбајҹан',
        ],

        // B
        //====================================================>
        'ba'         => [
            'name'   => 'Bashkir',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Башҡорт теле',
        ],
        'bas'        => [
            'name'   => 'Basa',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ɓàsàa',
        ],
        'be'         => [
            'name'   => 'Belarusian',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Беларуская',
        ],
        'bem'        => [
            'name'   => 'Bemba',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ichibemba',
        ],
        'bez'        => [
            'name'   => 'Bena',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Hibena',
        ],
        'bg'         => [
            'name'   => 'Bulgarian',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Български',
        ],
        'bh'         => [
            'name'   => 'Bihari',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bihari',
        ],
        'bi'         => [
            'name'   => 'Bislama',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bislama',
        ],
        'bm'         => [
            'name'   => 'Bambara',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bamanakan',
        ],
        'bn'         => [
            'name'   => 'Bengali',
            'script' => 'Beng',
            'dir'    => 'ltr',
            'native' => 'বাংলা',
        ],
        'bo'         => [
            'name'   => 'Tibetan',
            'script' => 'Tibt',
            'dir'    => 'ltr',
            'native' => 'པོད་སྐད་',
        ],
        'br'         => [
            'name'   => 'Breton',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Brezhoneg',
        ],
        'bra'        => [
            'name'   => 'Braj',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'ब्रज भाषा',
        ],
        'brx'        => [
            'name'   => 'Bodo',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'बड़ो',
        ],
        'bs'         => [
            'name'   => 'Bosnian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bosanski',
        ],
        'byn'        => [
            'name'   => 'Blin',
            'script' => 'Ethi',
            'dir'    => 'ltr',
            'native' => 'ብሊን',
        ],

        // C
        //====================================================>
        'ca'         => [
            'name'   => 'Catalan',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Català',
        ],
        'ca-valencia'=> [
            'name'   => 'Valencian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Valencià',
        ],
        'cch'        => [
            'name'   => 'Atsam',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Atsam',
        ],
        'ce'         => [
            'name'   => 'Chechen',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Нохчийн мотт',
        ],
        'cgg'        => [
            'name'   => 'Chiga',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Rukiga',
        ],
        'ch'         => [
            'name'   => 'Chamorro',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Chamoru',
        ],
        'chr'        => [
            'name'   => 'Cherokee',
            'script' => 'Cher',
            'dir'    => 'ltr',
            'native' => 'ᏣᎳᎩ',
        ],
        'co'         => [
            'name'   => 'Corsican',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Corsu',
        ],
        'cr'         => [
            'name'   => 'Cree',
            'script' => 'Cans',
            'dir'    => 'ltr',
            'native' => 'ᓀᐦᐃᔭᐍᐏᐣ',
        ],
        'cs'         => [
            'name'   => 'Czech',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Čeština',
        ],
        'cu'         => [
            'name'   => 'Church Slavic',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Ѩзыкъ словѣньскъ',
        ],
        'cv'         => [
            'name'   => 'Chuvash',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Чӑваш чӗлхи',
        ],
        'cy'         => [
            'name'   => 'Welsh',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Cymraeg',
        ],

        // D
        //====================================================>
        'da'         => [
            'name'   => 'Danish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Dansk',
        ],
        'dav'        => [
            'name'   => 'Dawida',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kitaita',
        ],
        'de'         => [
            'name'   => 'German',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Deutsch',
        ],
        'de-AT'      => [
            'name'   => 'Austrian German',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Österreichisches Deutsch',
        ],
        'de-CH'      => [
            'name'   => 'Swiss High German',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Schweizer Hochdeutsch',
        ],
        'dje'        => [
            'name'   => 'Zarma',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Zarmaciine',
        ],
        'doi'        => [
            'name'   => 'Dogri',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'डोगरी',
        ],
        'dua'        => [
            'name'   => 'Duala',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Duálá',
        ],
        'dv'         => [
            'name'   => 'Divehi',
            'script' => 'Thaa',
            'dir'    => 'rtl',
            'native' => 'ދިވެހިބަސް',
        ],
        'dyo'        => [
            'name'   => 'Jola-Fonyi',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Joola',
        ],
        'dz'         => [
            'name'   => 'Dzongkha',
            'script' => 'Tibt',
            'dir'    => 'ltr',
            'native' => 'རྫོང་ཁ',
        ],

        // E
        //====================================================>
        'ebu'        => [
            'name'   => 'Kiembu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kĩembu',
        ],
        'ee'         => [
            'name'   => 'Ewe',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Eʋegbe',
        ],
        'en'         => [
            'name'   => 'English',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'English',
        ],
        'en-AU'      => [
            'name'   => 'Australian English',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Australian English',
        ],
        'en-GB'      => [
            'name'   => 'British English',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'British English',
        ],
        'en-US'      => [
            'name'   => 'U.S. English',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'U.S. English',
        ],
        'el'         => [
            'name'   => 'Greek',
            'script' => 'Grek',
            'dir'    => 'ltr',
            'native' => 'Ελληνικά',
        ],
        'eo'         => [
            'name'   => 'Esperanto',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Esperanto',
        ],
        'es'         => [
            'name'   => 'Spanish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Español',
        ],
        'et'         => [
            'name'   => 'Estonian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Eesti',
        ],
        'eu'         => [
            'name'   => 'Basque',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Euskara',
        ],
        'ewo'        => [
            'name'   => 'Ewondo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ewondo',
        ],

        // F
        //====================================================>
        'fa'         => [
            'name'   => 'Persian',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'فارسی',
        ],
        'ff'         => [
            'name'   => 'Fulah',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Pulaar',
        ],
        'fi'         => [
            'name'   => 'Finnish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Suomi',
        ],
        'fil'        => [
            'name'   => 'Filipino',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Filipino',
        ],
        'fj'         => [
            'name'   => 'Fijian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Vosa Vakaviti',
        ],
        'fo'         => [
            'name'   => 'Faroese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Føroyskt',
        ],
        'fr'         => [
            'name'   => 'French',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Français',
        ],
        'fr-CA'      => [
            'name'   => 'Canadian French',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Français canadien',
        ],
        'fur'        => [
            'name'   => 'Friulian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Furlan',
        ],
        'fy'         => [
            'name'   => 'Western Frisian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Frysk',
        ],

        // G
        //====================================================>
        'ga'         => [
            'name'   => 'Irish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Gaeilge',
        ],
        'gaa'        => [
            'name'   => 'Ga',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ga',
        ],
        'gd'         => [
            'name'   => 'Scottish Gaelic',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Gàidhlig',
        ],
        'gl'         => [
            'name'   => 'Galician',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Galego',
        ],
        'gn'         => [
            'name'   => 'Guaraní',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Avañe’ẽ',
        ],
        'gsw'        => [
            'name'   => 'Swiss German',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Schwiizertüütsch',
        ],
        'gu'         => [
            'name'   => 'Gujarati',
            'script' => 'Gujr',
            'dir'    => 'ltr',
            'native' => 'ગુજરાતી',
        ],
        'guz'        => [
            'name'   => 'Ekegusii',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ekegusii',
        ],
        'gv'         => [
            'name'   => 'Manx',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Gaelg',
        ],

        // H
        //====================================================>
        'ha'         => [
            'name'   => 'Hausa',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Hausa',
        ],
        'haw'        => [
            'name'   => 'Hawaiian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'ʻŌlelo Hawaiʻi',
        ],
        'he'         => [
            'name'   => 'Hebrew',
            'script' => 'Hebr',
            'dir'    => 'rtl',
            'native' => 'עברית',
        ],
        'hi'         => [
            'name'   => 'Hindi',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'हिन्दी',
        ],
        'ho'         => [
            'name'   => 'Hiri Motu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Hiri Motu',
        ],
        'hr'         => [
            'name'   => 'Croatian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Hrvatski',
        ],
        'ht'         => [
            'name'   => 'Haitian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kreyòl ayisyen',
        ],
        'hu'         => [
            'name'   => 'Hungarian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Magyar',
        ],
        'hy'         => [
            'name'   => 'Armenian',
            'script' => 'Armn',
            'dir'    => 'ltr',
            'native' => 'Հայերէն',
        ],
        'hz'         => [
            'name'   => 'Herero',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Otjiherero',
        ],

        // I
        //====================================================>
        'ia'         => [
            'name'   => 'Interlingua',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Interlingua',
        ],
        'id'         => [
            'name'   => 'Indonesian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bahasa Indonesia',
        ],
        'ig'         => [
            'name'   => 'Igbo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Igbo',
        ],
        'ii'         => [
            'name'   => 'Sichuan Yi',
            'script' => 'Yiii',
            'dir'    => 'ltr',
            'native' => 'ꆈꌠꉙ',
        ],
        'ik'         => [
            'name'   => 'Inupiaq',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Iñupiaq',
        ],
        'io'         => [
            'name'   => 'Ido',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ido',
        ],
        'is'         => [
            'name'   => 'Icelandic',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Íslenska',
        ],
        'it'         => [
            'name'   => 'Italian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Italiano',
        ],
        'iu'         => [
            'name'   => 'Inuktitut (Canadian Aboriginal Syllabics)',
            'script' => 'Cans',
            'dir'    => 'ltr',
            'native' => 'ᐃᓄᒃᑎᑐᑦ',
        ],
        'iu-Latin'   => [
            'name'   => 'Inuktitut (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Inuktitut',
        ],

        // J
        //====================================================>
        'ja'         => [
            'name'   => 'Japanese',
            'script' => 'Jpan',
            'dir'    => 'ltr',
            'native' => '日本語',
        ],
        'jmc'        => [
            'name'   => 'Machame',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kimachame',
        ],
        'jv'         => [
            'name'   => 'Javanese (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Basa Jawa',
        ],
        'jv-Java'    => [
            'name'   => 'Javanese (Javanese)',
            'script' => 'Java',
            'dir'    => 'ltr',
            'native' => 'ꦧꦱꦗꦮ',
        ],

        // K
        //====================================================>
        'ka'         => [
            'name'   => 'Georgian',
            'script' => 'Geor',
            'dir'    => 'ltr',
            'native' => 'ქართული',
        ],
        'kab'        => [
            'name'   => 'Kabyle',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Taqbaylit',
        ],
        'kaj'        => [
            'name'   => 'Jju',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kaje',
        ],
        'kam'        => [
            'name'   => 'Kamba',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kikamba',
        ],
        'kcg'        => [
            'name'   => 'Tyap',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Katab',
        ],
        'kde'        => [
            'name'   => 'Makonde',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Chimakonde',
        ],
        'kea'        => [
            'name'   => 'Kabuverdianu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kabuverdianu',
        ],
        'kg'         => [
            'name'   => 'Kongo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kikongo',
        ],
        'khq'        => [
            'name'   => 'Koyra Chiini',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Koyra ciini',
        ],
        'ki'         => [
            'name'   => 'Kikuyu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Gikuyu',
        ],
        'kj'         => [
            'name'   => 'Kuanyama',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kwanyama',
        ],
        'kk'         => [
            'name'   => 'Kazakh',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Қазақ тілі',
        ],
        'kl'         => [
            'name'   => 'Kalaallisut',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kalaallisut',
        ],
        'kln'        => [
            'name'   => 'Kalenjin',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kalenjin',
        ],
        'km'         => [
            'name'   => 'Khmer',
            'script' => 'Khmr',
            'dir'    => 'ltr',
            'native' => 'ភាសាខ្មែរ',
        ],
        'kn'         => [
            'name'   => 'Kannada',
            'script' => 'Knda',
            'dir'    => 'ltr',
            'native' => 'ಕನ್ನಡ',
        ],
        'ko'         => [
            'name'   => 'Korean',
            'script' => 'Hang',
            'dir'    => 'ltr',
            'native' => '한국어',
        ],
        'kok'        => [
            'name'   => 'Konkani',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'कोंकणी',
        ],
        'kr'         => [
            'name'   => 'Kanuri',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kanuri',
        ],
        'ks'         => [
            'name'   => 'Kashmiri (Arabic)',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'کأشُر',
        ],
        'ks-Deva'    => [
            'name'   => 'Kashmiri (Devaganari)',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'कॉशुर',
        ],
        'ksb'        => [
            'name'   => 'Shambala',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kishambaa',
        ],
        'ksf'        => [
            'name'   => 'Bafia',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Rikpa',
        ],
        'ksh'        => [
            'name'   => 'Kölsch',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kölsch',
        ],
        'ku'         => [
            'name'   => 'Kurdish',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'کوردی',
        ],
        'kv'         => [
            'name'   => 'Komi',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Коми кыв',
        ],
        'kw'         => [
            'name'   => 'Cornish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kernewek',
        ],
        'ky'         => [
            'name'   => 'Kyrgyz',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Кыргыз',
        ],

        // L
        //====================================================>
        'la'         => [
            'name'   => 'Latin',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Latine',
        ],
        'lag'        => [
            'name'   => 'Langi',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kɨlaangi',
        ],
        'lah'        => [
            'name'   => 'Lahnda',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Lahnda',
        ],
        'lb'         => [
            'name'   => 'Luxembourgish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Lëtzebuergesch',
        ],
        'lg'         => [
            'name'   => 'Ganda',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Luganda',
        ],
        'li'         => [
            'name'   => 'Limburgish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Limburgs',
        ],
        'ln'         => [
            'name'   => 'Lingala',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Lingála',
        ],
        'lo'         => [
            'name'   => 'Lao',
            'script' => 'Laoo',
            'dir'    => 'ltr',
            'native' => 'ລາວ',
        ],
        'lt'         => [
            'name'   => 'Lithuanian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Lietuvių',
        ],
        'lu'         => [
            'name'   => 'Luba-Katanga',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tshiluba',
        ],
        'luo'        => [
            'name'   => 'Luo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Dholuo',
        ],
        'luy'        => [
            'name'   => 'Oluluyia',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Luluhia',
        ],
        'lv'         => [
            'name'   => 'Latvian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Latviešu',
        ],

        // M
        //====================================================>
        'mai'        => [
            'name'   => 'Maithili',
            'script' => 'Tirh',
            'dir'    => 'ltr',
            'native' => 'मैथिली',
        ],
        'mas'        => [
            'name'   => 'Masai',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ɔl-Maa',
        ],
        'mer'        => [
            'name'   => 'Kimîîru',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kĩmĩrũ',
        ],
        'mfe'        => [
            'name'   => 'Morisyen',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kreol morisien',
        ],
        'mg'         => [
            'name'   => 'Malagasy',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Malagasy',
        ],
        'mgh'        => [
            'name'   => 'Makhuwa-Meetto',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Makua',
        ],
        'mh'         => [
            'name'   => 'Marshallese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kajin M̧ajeļ',
        ],
        'mi'         => [
            'name'   => 'Māori',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Māori',
        ],
        'mk'         => [
            'name'   => 'Macedonian',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Македонски',
        ],
        'ml'         => [
            'name'   => 'Malayalam',
            'script' => 'Mlym',
            'dir'    => 'ltr',
            'native' => 'മലയാളം',
        ],
        'mn'         => [
            'name'   => 'Mongolian (Cyrillic)',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Монгол',
        ],
        'mn-Mong'    => [
            'name'   => 'Mongolian (Mongolian)',
            'script' => 'Mong',
            'dir'    => 'rtl',
            'native' => 'ᠮᠣᠨᠭᠭᠣᠯ ᠬᠡᠯᠡ',
        ],
        'mni'        => [
            'name'   => 'Manipuri',
            'script' => 'Beng',
            'dir'    => 'ltr',
            'native' => 'মৈতৈ',
        ],
        'mr'         => [
            'name'   => 'Marathi',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'मराठी',
        ],
        'ms'         => [
            'name'   => 'Malay',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bahasa Melayu',
        ],
        'mt'         => [
            'name'   => 'Maltese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Malti',
        ],
        'mtr'        => [
            'name'   => 'Mewari',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Mewari',
        ],
        'mua'        => [
            'name'   => 'Mundang',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Mundang',
        ],
        'my'         => [
            'name'   => 'Burmese',
            'script' => 'Mymr',
            'dir'    => 'ltr',
            'native' => 'မြန်မာဘာသာ',
        ],

        // N
        //====================================================>
        'na'         => [
            'name'   => 'Nauru',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ekakairũ Naoero',
        ],
        'naq'        => [
            'name'   => 'Nama',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Khoekhoegowab',
        ],
        'nb'         => [
            'name'   => 'Norwegian Bokmål',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Bokmål',
        ],
        'nd'         => [
            'name'   => 'North Ndebele',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'IsiNdebele',
        ],
        'nds'        => [
            'name'   => 'Low German',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Plattdüütsch',
        ],
        'ne'         => [
            'name'   => 'Nepali',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'नेपाली',
        ],
        'ng'         => [
            'name'   => 'Ndonga',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'OshiNdonga',
        ],
        'nl'         => [
            'name'   => 'Dutch',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Nederlands',
        ],
        'nmg'        => [
            'name'   => 'Kwasio',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ngumba',
        ],
        'nn'         => [
            'name'   => 'Norwegian Nynorsk',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Nynorsk',
        ],
        'nr'         => [
            'name'   => 'South Ndebele',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'IsiNdebele',
        ],
        'nso'        => [
            'name'   => 'Northern Sotho',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Sesotho sa Leboa',
        ],
        'nus'        => [
            'name'   => 'Nuer',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Thok Nath',
        ],
        'nv'         => [
            'name'   => 'Navajo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Diné bizaad',
        ],
        'ny'         => [
            'name'   => 'Chewa',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'ChiCheŵa',
        ],
        'nyn'        => [
            'name'   => 'Nyankole',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Runyankore',
        ],

        // O
        //====================================================>
        'oc'         => [
            'name'   => 'Occitan',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Occitan',
        ],
        'oj'         => [
            'name'   => 'Ojibwa',
            'script' => 'Cans',
            'dir'    => 'ltr',
            'native' => 'ᐊᓂᔑᓈᐯᒧᐎᓐ',
        ],
        'om'         => [
            'name'   => 'Oromo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Oromoo',
        ],
        'or'         => [
            'name'   => 'Oriya',
            'script' => 'Orya',
            'dir'    => 'ltr',
            'native' => 'ଓଡ଼ିଆ',
        ],
        'os'         => [
            'name'   => 'Ossetic',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Ирон',
        ],

        // P
        //====================================================>
        'pa'         => [
            'name'   => 'Punjabi (Gurmukhi)',
            'script' => 'Guru',
            'dir'    => 'ltr',
            'native' => 'ਪੰਜਾਬੀ',
        ],
        'pa-Arab'    => [
            'name'   => 'Punjabi (Arabic)',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'پنجاب',
        ],
        'pi'         => [
            'name'   => 'Pahari-Potwari',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Pāli',
        ],
        'pl'         => [
            'name'   => 'Polish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Polski',
        ],
        'pra'        => [
            'name'   => 'Prakrit',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'प्राकृत',
        ],
        'ps'         => [
            'name'   => 'Pashto',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'پښتو',
        ],
        'pt'         => [
            'name'   => 'Portuguese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Português',
        ],
        'pt-BR'      => [
            'name'   => 'Brazilian Portuguese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Português do Brasil',
        ],

        // Q
        //====================================================>
        'qu'         => [
            'name'   => 'Quechua',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Runa Simi',
        ],

        // R
        //====================================================>
        'raj'        => [
            'name'   => 'Rajasthani',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'राजस्थानी',
        ],
        'ro'         => [
            'name'   => 'Romanian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Română',
        ],
        'rof'        => [
            'name'   => 'Rombo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kihorombo',
        ],
        'rm'         => [
            'name'   => 'Romansh',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Rumantsch',
        ],
        'rn'         => [
            'name'   => 'Rundi',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ikirundi',
        ],
        'ru'         => [
            'name'   => 'Russian',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Русский',
        ],
        'rw'         => [
            'name'   => 'Kinyarwanda',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kinyarwanda',
        ],
        'rwk'        => [
            'name'   => 'Rwa',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kiruwa',
        ],

        // S
        //====================================================>
        'sa'         => [
            'name'   => 'Sanskrit',
            'script' => 'Deva',
            'dir'    => 'ltr',
            'native' => 'संस्कृतम्',
        ],
        'sah'        => [
            'name'   => 'Yakut',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Саха тыла',
        ],
        'saq'        => [
            'name'   => 'Samburu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kisampur',
        ],
        'sbp'        => [
            'name'   => 'Sileibi',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Ishisangu',
        ],
        'sc'         => [
            'name'   => 'Sardinian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Sardu',
        ],
        'sd'         => [
            'name'   => 'Sindhi',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'سنڌي',
        ],
        'se'         => [
            'name'   => 'Northern Sami',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Davvisámegiella',
        ],
        'seh'        => [
            'name'   => 'Sena',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Sena',
        ],
        'ses'        => [
            'name'   => 'Songhay',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Koyraboro senni',
        ],
        'sg'         => [
            'name'   => 'Sango',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Sängö',
        ],
        'sh'         => [
            'name'   => 'Serbo-Croatian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Srpskohrvatski',
        ],
        'shi'        => [
            'name'   => 'Tachelhit (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tashelhit',
        ],
        'shi-Tfng'   => [
            'name'   => 'Tachelhit (Tifinagh)',
            'script' => 'Tfng',
            'dir'    => 'rtl',
            'native' => 'ⵜⴰⵎⴰⵣⵉⵖⵜ',
        ],
        'si'         => [
            'name'   => 'Sinhala',
            'script' => 'Sinh',
            'dir'    => 'ltr',
            'native' => 'සිංහල',
        ],
        'sid'        => [
            'name'   => 'Sidamo',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Sidaamu Afo',
        ],
        'sk'         => [
            'name'   => 'Slovak',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Slovenčina',
        ],
        'sl'         => [
            'name'   => 'Slovene',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Slovenščina',
        ],
        'sm'         => [
            'name'   => 'Samoan',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Gagana fa’a Sāmoa',
        ],
        'sn'         => [
            'name'   => 'Shona',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'ChiShona',
        ],
        'so'         => [
            'name'   => 'Somali',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Soomaali',
        ],
        'sq'         => [
            'name'   => 'Albanian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Shqip',
        ],
        'sr'         => [
            'name'   => 'Serbian (Cyrillic)',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Српски',
        ],
        'sr-Latin'   => [
            'name'   => 'Serbian (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Srpski',
        ],
        'ss'         => [
            'name'   => 'Swati',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Siswati',
        ],
        'ssy'        => [
            'name'   => 'Saho',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Saho',
        ],
        'st'         => [
            'name'   => 'Southern Sotho',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Sesotho',
        ],
        'su'         => [
            'name'   => 'Sundanese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Basa Sunda',
        ],
        'sv'         => [
            'name'   => 'Swedish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Svenska',
        ],
        'sw'         => [
            'name'   => 'Swahili',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kiswahili',
        ],
        'swc'        => [
            'name'   => 'Congo Swahili',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kiswahili ya Kongo',
        ],

        // T
        //====================================================>
        'ta'         => [
            'name'   => 'Tamil',
            'script' => 'Taml',
            'dir'    => 'ltr',
            'native' => 'தமிழ்',
        ],
        'te'         => [
            'name'   => 'Telugu',
            'script' => 'Telu',
            'dir'    => 'ltr',
            'native' => 'తెలుగు',
        ],
        'teo'        => [
            'name'   => 'Teso',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Kiteso',
        ],
        'tg'         => [
            'name'   => 'Tajik (Cyrillic)',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Тоҷикӣ',
        ],
        'tg-Arab'    => [
            'name'   => 'Tajik (Arabic)',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'تاجیکی',
        ],
        'tg-Latin'   => [
            'name'   => 'Tajik (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tojikī',
        ],
        'th'         => [
            'name'   => 'Thai',
            'script' => 'Thai',
            'dir'    => 'ltr',
            'native' => 'ไทย',
        ],
        'ti'         => [
            'name'   => 'Tigrinya',
            'script' => 'Ethi',
            'dir'    => 'ltr',
            'native' => 'ትግርኛ',
        ],
        'tig'        => [
            'name'   => 'Tigre',
            'script' => 'Ethi',
            'dir'    => 'ltr',
            'native' => 'ትግረ',
        ],
        'tk'         => [
            'name'   => 'Turkmen',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Түркменче',
        ],
        'tl'         => [
            'name'   => 'Tagalog',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tagalog',
        ],
        'tn'         => [
            'name'   => 'Tswana',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Setswana',
        ],
        'to'         => [
            'name'   => 'Tongan',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Lea fakatonga',
        ],
        'tr'         => [
            'name'   => 'Turkish',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Türkçe',
        ],
        'trv'        => [
            'name'   => 'Taroko',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Seediq',
        ],
        'ts'         => [
            'name'   => 'Tsonga',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Xitsonga',
        ],
        'tt'         => [
            'name'   => 'Tatar',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Татар теле',
        ],
        'tw'         => [
            'name'   => 'Twi',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Twi',
        ],
        'twq'        => [
            'name'   => 'Tasawaq',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tasawaq senni',
        ],
        'ty'         => [
            'name'   => 'Tahitian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Reo Māohi',
        ],
        'tzm'        => [
            'name'   => 'Central Atlas Tamazight (Tifinagh)',
            'script' => 'Tfng',
            'dir'    => 'rtl',
            'native' => 'ⵜⴰⵎⴰⵣⵉⵖⵜ',
        ],
        'tzm-Latin'  => [
            'name'   => 'Central Atlas Tamazight (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tamazight',
        ],

        // U
        //====================================================>
        'ug'         => [
            'name'   => 'Uyghur',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'ئۇيغۇرچە',
        ],
        'uk'         => [
            'name'   => 'Ukrainian',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Українська',
        ],
        'ur'         => [
            'name'   => 'Urdu',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'اردو',
        ],
        'uz'         => [
            'name'   => 'Uzbek (Cyrillic)',
            'script' => 'Cyrl',
            'dir'    => 'ltr',
            'native' => 'Ўзбек',
        ],
        'uz-Arab'    => [
            'name'   => 'Uzbek (Arabic)',
            'script' => 'Arab',
            'dir'    => 'rtl',
            'native' => 'اۉزبېک',
        ],
        'uz-Latin'   => [
            'name'   => 'Uzbek (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Oʼzbekcha',
        ],

        // V
        //====================================================>
        'vai'        => [
            'name'   => 'Vai (Vai)',
            'script' => 'Vaii',
            'dir'    => 'ltr',
            'native' => 'ꕙꔤ',
        ],
        'vai-Latin'  => [
            'name'   => 'Vai (Latin)',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Viyamíĩ',
        ],
        've'         => [
            'name'   => 'Venda',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tshivenḓa',
        ],
        'vi'         => [
            'name'   => 'Vietnamese',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Tiếng Việt',
        ],
        'vo'         => [
            'name'   => 'Volapük',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Volapük',
        ],

        // W
        //====================================================>
        'wa'         => [
            'name'   => 'Walloon',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Walon',
        ],
        'wae'        => [
            'name'   => 'Walser',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Walser',
        ],
        'wal'        => [
            'name'   => 'Wolaytta',
            'script' => 'Ethi',
            'dir'    => 'ltr',
            'native' => 'ወላይታቱ',
        ],
        'wen'        => [
            'name'   => 'Sorbian',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Wendic',
        ],
        'wo'         => [
            'name'   => 'Wolof',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Wolof',
        ],

        // X
        //====================================================>
        'xh'         => [
            'name'   => 'Xhosa',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'IsiXhosa',
        ],
        'xog'        => [
            'name'   => 'Soga',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Olusoga',
        ],

        // Y
        //====================================================>
        'yav'        => [
            'name'   => 'Yangben',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Nuasue',
        ],
        'yi'         => [
            'name'   => 'Yiddish',
            'script' => 'Hebr',
            'dir'    => 'rtl',
            'native' => 'ייִדיש',
        ],
        'yo'         => [
            'name'   => 'Yoruba',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'Èdè Yorùbá',
        ],
        'yue'        => [
            'name'   => 'Yue',
            'script' => 'Hant',
            'dir'    => 'ltr',
            'native' => '廣州話',
        ],

        // Z
        //====================================================>
        'zh'         => [
            'name'   => 'Chinese (Simplified)',
            'script' => 'Hans',
            'dir'    => 'ltr',
            'native' => '简体中文',
        ],
        'zh-Hant'    => [
            'name'   => 'Chinese (Traditional)',
            'script' => 'Hant',
            'dir'    => 'ltr',
            'native' => '繁體中文',
        ],
        'zu'         => [
            'name'   => 'Zulu',
            'script' => 'Latn',
            'dir'    => 'ltr',
            'native' => 'IsiZulu',
        ],
    ],
];
