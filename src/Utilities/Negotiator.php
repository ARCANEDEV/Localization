<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\Negotiator as NegotiatorContract;
use Arcanedev\Localization\Entities\LocaleCollection;
use Illuminate\Http\Request;
use Locale;

/**
 * Class     Negotiator
 *
 * @package  Arcanedev\Localization\Utilities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * Negotiates language with the user's browser through the Accept-Language HTTP header or the user's host address.
 * Language codes are generally in the form "ll" for a language spoken in only one country, or "ll-CC" for a
 * language spoken in a particular country.  For example, U.S. English is "en-US", while British English is "en-UK".
 * Portuguese as spoken in Portugal is "pt-PT", while Brazilian Portuguese is "pt-BR".
 *
 * This function is based on negotiateLanguage from Pear HTTP2
 * http://pear.php.net/package/HTTP2/
 *
 * Quality factors in the Accept-Language: header are supported, e.g.:
 * Accept-Language: en-UK;q=0.7, en-US;q=0.6, no, dk;q=0.8
 */
class Negotiator implements NegotiatorContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Default Locale.
     *
     * @var string
     */
    private $defaultLocale;

    /**
     * The supported locales collection.
     *
     * @var \Arcanedev\Localization\Entities\LocaleCollection
     */
    private $supportedLocales;

    /**
     * The HTTP request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Negotiator instance.
     *
     * @param  string                                             $defaultLocale
     * @param  \Arcanedev\Localization\Entities\LocaleCollection  $supportedLanguages
     */
    public function __construct($defaultLocale, LocaleCollection $supportedLanguages)
    {
        $this->defaultLocale    = $defaultLocale;
        $this->supportedLocales = $supportedLanguages;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Negotiate the request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    public function negotiate(Request $request)
    {
        $this->request = $request;

        $locale = $this->getFromAcceptedLanguagesHeader();

        if ( ! is_null($locale)) return $locale;

        $locale = $this->getFromHttpAcceptedLanguagesServer();

        if ( ! is_null($locale)) return $locale;

        $locale = $this->getFromRemoteHostServer();

        if ( ! is_null($locale)) return $locale;

        // TODO: Adding negotiate form IP Address ??

        return $this->defaultLocale;
    }

    /**
     * Get locale from accepted languages header.
     *
     * @return null|string
     */
    private function getFromAcceptedLanguagesHeader()
    {
        $matches = $this->getMatchesFromAcceptedLanguages();

        if ($locale = $this->inSupportedLocales($matches)) {
            return $locale;
        }

        // If any (i.e. "*") is acceptable, return the first supported locale
        if (isset($matches['*'])) {
            return $this->supportedLocales->first()->key();
        }

        return null;
    }

    /**
     * Get locale from http accepted languages server.
     *
     * @return null|string
     */
    private function getFromHttpAcceptedLanguagesServer()
    {
        $httpAcceptLanguage = $this->request->server('HTTP_ACCEPT_LANGUAGE');

        // @codeCoverageIgnoreStart
        if ( ! class_exists('Locale') || empty($httpAcceptLanguage)) {
            return null;
        }
        // @codeCoverageIgnoreEnd

        $locale = Locale::acceptFromHttp($httpAcceptLanguage);

        if ($this->isSupported($locale)) {
            return $locale;
        }

        return null;
    }

    /**
     * Get locale from remote host server.
     *
     * @return null|string
     */
    private function getFromRemoteHostServer()
    {
        if (empty($remoteHost = $this->request->server('REMOTE_HOST')))
            return null;

        $remoteHost = explode('.', $remoteHost);
        $locale     = strtolower(end($remoteHost));

        return $this->isSupported($locale) ? $locale : null;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if matches a supported locale.
     *
     * @param  array  $matches
     *
     * @return null|string
     */
    private function inSupportedLocales(array $matches)
    {
        foreach (array_keys($matches) as $locale) {
            if ($this->isSupported($locale))
                return $locale;

            // Search for acceptable locale by 'regional' => 'fr_FR' match.
            foreach ($this->supportedLocales as $key => $entity) {
                /** @var \Arcanedev\Localization\Entities\Locale $entity */
                if ($entity->regional() == $locale)
                    return $key;
            }
        }

        return null;
    }

    /**
     * Check if the locale is supported.
     *
     * @param  string  $locale
     *
     * @return bool
     */
    private function isSupported($locale)
    {
        return $this->supportedLocales->has($locale);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Return all the accepted languages from the browser
     *
     * @return array  -  Matches from the header field Accept-Languages
     */
    private function getMatchesFromAcceptedLanguages()
    {
        $matches = [];

        $acceptLanguages = $this->request->header('Accept-Language');

        if ( ! empty($acceptLanguages)) {
            $acceptLanguages = explode(',', $acceptLanguages);

            $genericMatches = $this->retrieveGenericMatches($acceptLanguages, $matches);

            $matches = array_merge($genericMatches, $matches);
            arsort($matches, SORT_NUMERIC);
        }

        return $matches;
    }

    /**
     * Get the generic matches.
     *
     * @param  array  $acceptLanguages
     * @param  array  $matches
     *
     * @return array
     */
    private function retrieveGenericMatches($acceptLanguages, &$matches)
    {
        $genericMatches = [];

        foreach ($acceptLanguages as $option) {
            $option  = array_map('trim', explode(';', $option));
            $locale  = $option[0];
            $quality = $this->getQualityFactor($locale, $option);

            // Unweighted values, get high weight by their position in the list
            $quality          = isset($quality) ? $quality : 1000 - count($matches);
            $matches[$locale] = $quality;

            // If for some reason the Accept-Language header only sends language with country we should make
            // the language without country an accepted option, with a value less than it's parent.
            $localeOptions = explode('-', $locale);
            array_pop($localeOptions);

            while ( ! empty($localeOptions)) {
                //The new generic option needs to be slightly less important than it's base
                $quality -= 0.001;
                $opt      = implode('-', $localeOptions);

                if (empty($genericMatches[$opt]) || $genericMatches[$opt] > $quality) {
                    $genericMatches[$opt] = $quality;
                }

                array_pop($localeOptions);
            }
        }

        return $genericMatches;
    }

    /**
     * Get the quality factor.
     *
     * @param  string  $locale
     * @param  array   $option
     *
     * @return float|null
     */
    private function getQualityFactor($locale, $option)
    {
        if (isset($option[1])) {
            return (float) str_replace('q=', '', $option[1]);
        }

        // Assign default low weight for generic values
        if ($locale === '*/*') {
            return 0.01;
        }

        if (substr($locale, -1) === '*') {
            return 0.02;
        }

        return null;
    }
}
