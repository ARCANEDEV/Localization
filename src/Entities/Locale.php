<?php namespace Arcanedev\Localization\Entities;
use Arcanedev\Localization\Exceptions\InvalidLocaleDirectionException;

/**
 * Class     Locale
 *
 * @package  Arcanedev\Localization\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Locale
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Locale key.
     *
     * @var string
     */
    public $key;

    /**
     * Locale name.
     *
     * @var string
     */
    public $name;

    /**
     * Locale script.
     *
     * @var string
     */
    public $script;

    /**
     * Locale direction.
     *
     * @var string
     */
    public $direction;

    /**
     * Locale native.
     *
     * @var string
     */
    public $native;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create Locale instance.
     *
     * @param  string  $key
     * @param  array   $data
     */
    public function __construct($key, array $data)
    {
        $this->key = $key;
        $this->setName($data['name']);
        $this->setScript($data['script']);
        $this->setDirection($data['dir']);
        $this->setNative($data['native']);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set name.
     *
     * @param  string  $name
     *
     * @return self
     */
    private function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set Script.
     *
     * @param  string  $script
     *
     * @return self
     */
    private function setScript($script)
    {
        $this->script = $script;

        return $this;
    }

    /**
     * Set Direction.
     *
     * @param  string $direction
     *
     * @return self
     */
    private function setDirection($direction)
    {
        $this->checkDirection($direction);

        $this->direction = $direction;

        return $this;
    }

    /**
     * Set Native.
     *
     * @param  string  $native
     *
     * @return self
     */
    private function setNative($native)
    {
        $this->native = $native;

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check locale direction.
     *
     * @param  string  $direction
     *
     * @throws InvalidLocaleDirectionException
     */
    private function checkDirection(&$direction)
    {
        $direction = strtolower($direction);

        if ( ! in_array($direction, ['ltr', 'rtl'])) {
            throw new InvalidLocaleDirectionException(
                'The direction [' . $direction . '] is invalid, '.
                'must be ltr (Left to Right) or rtl (Right to Left).'
            );
        }
    }
}
