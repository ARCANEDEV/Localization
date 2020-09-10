<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Traits;

use Arcanedev\Localization\Events\TranslationHasBeenSet;
use Arcanedev\Localization\Exceptions\UntranslatableAttributeException;
use Illuminate\Support\Str;

/**
 * Trait     HasTranslations
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property       array  attributes
 * @property-read  array  translations
 */
trait HasTranslations
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the translations.
     *
     * @return array
     */
    public function getTranslationsAttribute(): array
    {
        return collect($this->getTranslatableAttributes())
            ->mapWithKeys(function (string $key) {
                return [$key => $this->getTranslations($key)];
            })
            ->toArray();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the translatable attributes.
     *
     * @return array
     */
    abstract public function getTranslatableAttributes();

    /**
     * Get the translated attribute value.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        return $this->isTranslatableAttribute($key)
            ? $this->getTranslation($key, $this->getLocale())
            : parent::getAttributeValue($key);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return self
     */
    public function setAttribute($key, $value)
    {
        return ( ! $this->isTranslatableAttribute($key) || is_array($value))
            ? parent::setAttribute($key, $value)
            : $this->setTranslation($key, $this->getLocale(), $value);
    }

    /**
     * Get the translated attribute (alias).
     *
     * @param  string  $key
     * @param  string  $locale
     * @param  bool    $useFallbackLocale
     *
     * @return mixed
     */
    public function trans($key, $locale = '', bool $useFallbackLocale = true)
    {
        return $this->getTranslation($key, $locale, $useFallbackLocale);
    }

    /***
     * Get the translated attribute.
     *
     * @param  string  $key
     * @param  string  $locale
     * @param  bool    $useFallbackLocale
     *
     * @return mixed
     */
    public function getTranslation($key, $locale, $useFallbackLocale = true)
    {
        $locale       = $this->normalizeLocale($key, $locale, $useFallbackLocale);
        $translations = $this->getTranslations($key);
        $translation  = $translations[$locale] ?? '';

        return $this->hasGetMutator($key)
            ? $this->mutateAttribute($key, $translation)
            : $translation;
    }

    /**
     * Get the translations for the given key.
     *
     * @param  string|null  $key
     *
     * @return array
     */
    public function getTranslations($key = null)
    {
        if ($key !== null) {
            $this->guardAgainstNonTranslatableAttribute($key);

            return array_filter(json_decode($this->getAttributeFromArray($key) ?? '' ?: '{}', true) ?: [], function ($value) {
                return $value !== null && $value !== '';
            });
        }

        return array_reduce($this->getTranslatableAttributes(), function ($result, $item) {
            $result[$item] = $this->getTranslations($item);

            return $result;
        });
    }

    /**
     * Set a translation.
     *
     * @param  string  $key
     * @param  string  $locale
     * @param  string  $value
     *
     * @return self
     */
    public function setTranslation($key, $locale, $value)
    {
        $this->guardAgainstNonTranslatableAttribute($key);

        $translations = $this->getTranslations($key);
        $oldValue     = $translations[$locale] ?? '';

        if ($this->hasSetMutator($key)) {
            $this->{'set'.Str::studly($key).'Attribute'}($value);
            $value = $this->attributes[$key];
        }

        $translations[$locale]  = $value;
        $this->attributes[$key] = $this->asJson($translations);

        event(new TranslationHasBeenSet($this, $key, $locale, $oldValue, $value));

        return $this;
    }

    /**
     * Set the translations.
     *
     * @param  string  $key
     * @param  array   $translations
     *
     * @return self
     */
    public function setTranslations($key, array $translations)
    {
        $this->guardAgainstNonTranslatableAttribute($key);

        foreach ($translations as $locale => $translation) {
            $this->setTranslation($key, $locale, $translation);
        }

        return $this;
    }

    /**
     * Forget a translation.
     *
     * @param  string  $key
     * @param  string  $locale
     *
     * @return self
     */
    public function forgetTranslation($key, $locale)
    {
        $translations = $this->getTranslations($key);
        unset($translations[$locale]);

        if ($this->hasSetMutator($key))
            $this->attributes[$key] = $this->asJson($translations);
        else
            $this->setAttribute($key, $translations);

        return $this;
    }

    /**
     * Forget all the translations by the given locale.
     *
     * @param  string  $locale
     *
     * @return self
     */
    public function flushTranslations($locale)
    {
        collect($this->getTranslatableAttributes())->each(function (string $attribute) use ($locale) {
            $this->forgetTranslation($attribute, $locale);
        });

        return $this;
    }

    /**
     * Get the translated attribute's locales
     *
     * @param  string  $key
     *
     * @return array
     */
    public function getTranslatedLocales($key): array
    {
        return array_keys($this->getTranslations($key));
    }

    /**
     * Check if has a translation.
     *
     * @param  string       $key
     * @param  string|null  $locale
     *
     * @return bool
     */
    public function hasTranslation(string $key, string $locale = null): bool
    {
        $locale = $locale ?: $this->getLocale();

        return isset($this->getTranslations($key)[$locale]);
    }

    /**
     * Check if the attribute is translatable.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isTranslatableAttribute($key)
    {
        return in_array($key, $this->getTranslatableAttributes());
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the locale.
     *
     * @return string
     */
    protected function getLocale(): string
    {
        return (string) config('app.locale');
    }

    /**
     * Guard against untranslatable attribute.
     *
     * @param  string  $key
     *
     * @throws \Arcanedev\Localization\Exceptions\UntranslatableAttributeException
     */
    protected function guardAgainstNonTranslatableAttribute($key)
    {
        if ( ! $this->isTranslatableAttribute($key)) {
            throw UntranslatableAttributeException::make($key, $this->getTranslatableAttributes());
        }
    }

    /**
     * Normalize the locale.
     *
     * @param  string  $key
     * @param  string  $locale
     * @param  bool    $useFallback
     *
     * @return string
     */
    protected function normalizeLocale($key, $locale, $useFallback)
    {
        if (in_array($locale, $this->getTranslatedLocales($key)) || ! $useFallback)
            return $locale;

        return config('app.fallback_locale') ?: $locale;
    }

    /* -----------------------------------------------------------------
     |  Eloquent Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the casts array.
     *
     * @return array
     */
    public function getCasts()
    {
        return array_merge(
            parent::getCasts(),
            array_fill_keys($this->getTranslatableAttributes(), 'array')
        );
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    abstract public function hasGetMutator($key);

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    abstract protected function mutateAttribute($key, $value);

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    abstract protected function getAttributeFromArray($key);

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     *
     * @return bool
     */
    abstract public function hasSetMutator($key);

    /**
     * Encode the given value as JSON.
     *
     * @param  mixed  $value
     *
     * @return string
     */
    abstract protected function asJson($value);
}
