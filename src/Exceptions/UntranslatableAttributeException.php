<?php namespace Arcanedev\Localization\Exceptions;

/**
 * Class     UntranslatableAttributeException
 *
 * @package  Arcanedev\Localization\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UntranslatableAttributeException extends LocalizationException 
{
    public static function make(string $key, array $translatableAttributes)
    {
        $translatableAttributes = implode(', ', $translatableAttributes);

        return new static(
            "The attribute `{$key}` is untranslatable because it's not available in the translatable array: `$translatableAttributes`"
        );
    }
}
