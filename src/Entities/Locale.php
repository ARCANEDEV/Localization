<?php namespace Arcanedev\Localization\Entities;

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
    private $key;

    /**
     * Locale name.
     *
     * @var string
     */
    private $name;

    /**
     * Locale script.
     *
     * @var string
     */
    private $script;

    /**
     * Locale direction.
     *
     * @var string
     */
    private $direction;

    /**
     * Locale native.
     *
     * @var string
     */
    private $native;

    /**
     * Default locale.
     *
     * @var bool
     */
    private $default = false;

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
        $this->setKey($key);
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
     * Get local key.
     *
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Set locale key.
     *
     * @param  string  $key
     *
     * @return self
     */
    private function setKey($key)
    {
        $this->key = $key;
        $this->setDefault();

        return $this;
    }

    /**
     * Get locale name.
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

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
     * Get locale Script.
     *
     * @return string
     */
    public function script()
    {
        return $this->script;
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
     * Get locale direction.
     *
     * @return string
     */
    public function direction()
    {
        if (empty($this->direction)) {
            $this->direction = in_array($this->script, [
                'Arab', 'Hebr', 'Mong', 'Tfng', 'Thaa'
            ]) ? 'rtl' : 'ltr';
        }

        return $this->direction;
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
        if ( ! empty($direction)) {
            $this->direction = strtolower($direction);
        }

        return $this;
    }

    /**
     * Get locale native.
     *
     * @return string
     */
    public function native()
    {
        return $this->native;
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

    /**
     * Check if it is a default locale.
     *
     * @return bool
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Set locale default.
     *
     * @return self
     */
    private function setDefault()
    {
        $this->default = $this->key === config('app.locale');

        return $this;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
}
