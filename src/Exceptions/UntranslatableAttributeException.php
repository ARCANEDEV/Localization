<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Exceptions;

/**
 * Class     UntranslatableAttributeException
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UntranslatableAttributeException extends LocalizationException
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  string  $key
     * @param  array   $translatableAttributes
     *
     * @return static
     */
    public static function make(string $key, array $translatableAttributes)
    {
        $translatableAttributes = implode(', ', $translatableAttributes);

        return new static(
            "The attribute `{$key}` is untranslatable because it's not available in the translatable array: `$translatableAttributes`"
        );
    }
}
