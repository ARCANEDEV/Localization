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

    /**
     * @var Request
     */
    private $request;

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
        $this->request  = $request;
        $matches        = $this->getMatchesFromAcceptedLanguages();

        foreach ($matches as $key => $q) {
            if ($this->supportedLocales->has($key)) {
                return $key;
            }
        }

        // If any (i.e. "*") is acceptable, return the first supported format
        if (isset($matches[ '*' ])) {
            /** @var \Arcanedev\Localization\Entities\Locale $locale */
            $locale = $this->supportedLocales->first();

            return $locale->key();
        }

        if (class_exists('Locale') && ! empty($this->request->server('HTTP_ACCEPT_LANGUAGE'))) {
            $httpAcceptLanguage = Locale::acceptFromHttp($this->request->server('HTTP_ACCEPT_LANGUAGE'));

            if ($this->supportedLocales->has($httpAcceptLanguage)) {
                return $httpAcceptLanguage;
            }
        }

        if ($this->request->server('REMOTE_HOST')) {
            $remote_host = explode('.', $this->request->server('REMOTE_HOST'));
            $lang        = strtolower(end($remote_host));

            if ($this->supportedLocales->has($lang)) {
                return $lang;
            }
        }

        return $this->defaultLocale;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Return all the accepted languages from the browser
     * @return array Matches from the header field Accept-Languages
     */
    private function getMatchesFromAcceptedLanguages()
    {
        $matches = [];

        if ($acceptLanguages = $this->request->header('Accept-Language')) {
            $acceptLanguages = explode(',', $acceptLanguages);
            $generic_matches = [];

            foreach ($acceptLanguages as $option) {
                $option = array_map('trim', explode(';', $option));
                $l      = $option[0];

                if (isset($option[1])) {
                    $q = (float) str_replace('q=', '', $option[1]);
                }
                else {
                    $q = null;

                    // Assign default low weight for generic values
                    if ($l == '*/*') {
                        $q = 0.01;
                    }
                    elseif (substr($l, -1) == '*') {
                        $q = 0.02;
                    }
                }

                // Unweighted values, get high weight by their position in the list
                $matches[$l] = $q = isset($q) ? $q : 1000 - count($matches);

                //If for some reason the Accept-Language header only sends language with country
                //we should make the language without country an accepted option, with a value
                //less than it's parent.
                $l_ops = explode('-', $l);
                array_pop($l_ops);

                while ( ! empty($l_ops)) {
                    //The new generic option needs to be slightly less important than it's base
                    $q -= 0.001;
                    $op = implode('-', $l_ops);

                    if (empty($generic_matches[ $op ]) || $generic_matches[$op] > $q) {
                        $generic_matches[ $op ] = $q;
                    }

                    array_pop($l_ops);
                }
            }

            $matches = array_merge($generic_matches, $matches);
            arsort($matches, SORT_NUMERIC);
        }

        return $matches;
    }
}
