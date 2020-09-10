<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Events;

/**
 * Class     TranslationHasBeenSet
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslationHasBeenSet
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var mixed */
    public $model;

    /** @var string */
    public $attribute;

    /** @var string */
    public $locale;

    /** @var mixed */
    public $oldValue;

    /** @var mixed */
    public $newValue;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * TranslationHasBeenSet constructor.
     *
     * @param  mixed   $model
     * @param  string  $attribute
     * @param  string  $locale
     * @param  mixed   $oldValue
     * @param  mixed   $newValue
     */
    public function __construct($model, $attribute, $locale, $oldValue, $newValue)
    {
        $this->model     = $model;
        $this->attribute = $attribute;
        $this->locale    = $locale;
        $this->oldValue  = $oldValue;
        $this->newValue  = $newValue;
    }
}
