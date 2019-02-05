<?php namespace Arcanedev\Localization\Tests\Stubs\Models;

use Arcanedev\Localization\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class     TranslatableModel
 *
 * @package  Arcanedev\Localization\Tests\Stubs\Models
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  int     id
 * @property  string  name
 * @property  string  slug
 */
class TranslatableModel extends Model
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasTranslations;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected $table    = 'translatable_table';

    protected $fillable = ['name', 'slug'];

    public $timestamps  = false;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the translatable attributes.
     *
     * @return array
     */
    public function getTranslatableAttributes()
    {
        return ['name', 'slug'];
    }

    /**
     * Get the name attribute (accessor).
     *
     * @param  string  $name
     *
     * @return string
     */
    public function getNameAttribute($name)
    {
        return Str::ucfirst($name);
    }

    /**
     * Set the slug attribute (mutator).
     *
     * @param  string  $slug
     */
    public function setSlugAttribute($slug)
    {
        $this->attributes['slug'] = Str::slug($slug);
    }
}
