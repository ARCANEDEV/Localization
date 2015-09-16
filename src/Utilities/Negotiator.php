<?php namespace Arcanedev\Localization\Utilities;

use Arcanedev\Localization\Contracts\NegotiatorInterface;
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
class Negotiator implements NegotiatorInterface
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
     * @var LocaleCollection
     */
    private $supportedLocales;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make Negotiator instance.
     *
     * @param  string            $defaultLocale
     * @param  LocaleCollection  $supportedLanguages
     */
    public function __construct($defaultLocale, $supportedLanguages)
    {
        $this->defaultLocale      = $defaultLocale;
        $this->supportedLocales   = $supportedLanguages;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Negotiate the request.
     *
     * @param  Request  $request
     *
     * @return string
     */
    public function negotiate(Request $request)
    {
        $matches = $this->getMatchesFromAcceptedLanguages($request);

        foreach (array_keys($matches) as $locale) {
            if ($this->isSupported($locale))
                return $locale;
        }

        // If any (i.e. "*") is acceptable, return the first supported format
        if (isset($matches[ '*' ])) {
            /** @var \Arcanedev\Localization\Entities\Locale $locale */
            $locale = $this->supportedLocales->first();

            return $locale->key();
        }

        if (class_exists('Locale') && ! empty($request->server('HTTP_ACCEPT_LANGUAGE'))) {
            $httpAcceptLanguage = Locale::acceptFromHttp($request->server('HTTP_ACCEPT_LANGUAGE'));

            if ($this->isSupported($httpAcceptLanguage))
                return $httpAcceptLanguage;
        }

        if ($request->server('REMOTE_HOST')) {
            $remote_host = explode('.', $request->server('REMOTE_HOST'));
            $locale      = strtolower(end($remote_host));

            if ($this->isSupported($locale))
                return $locale;
        }

        return $this->defaultLocale;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
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
     * @param  Request  $request
     *
     * @return array  -  Matches from the header field Accept-Languages
     */
    private function getMatchesFromAcceptedLanguages(Request $request)
    {
        $matches = [];

        if ($acceptLanguages = $request->header('Accept-Language')) {
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
