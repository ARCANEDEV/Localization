<?php namespace Arcanedev\Localization\Tests;

use Arcanedev\Localization\Events\TranslationHasBeenSet;
use Arcanedev\Localization\Tests\Stubs\Models\TranslatableModel;

/**
 * Class     TranslatableModelTest
 *
 * @package  Arcanedev\Localization\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TranslatableModelTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\Localization\Tests\Stubs\Models\TranslatableModel */
    protected $model;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__.'/fixtures/migrations'));

        $this->model = new TranslatableModel;
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_set_translated_values_when_creating_a_model()
    {
        $model = TranslatableModel::create([
            'name' => [
                'en' => 'Name',
            ],
        ]);

        static::assertSame('Name', $model->name);
    }

    /** @test */
    public function it_can_save_a_translated_attribute()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('Name', $this->model->name);
    }

    /** @test */
    public function it_can_translate_attribute_with_a_fallback_locale()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        $expected = 'Name';

        static::assertSame($expected, $this->model->getTranslation('name', 'fr'));
        static::assertSame($expected, $this->model->trans('name', 'fr'));
    }

    /** @test */
    public function it_must_return_empty_string_when_attribute_does_not_have_translation_and_fallback_locale_is_disabled()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('', $this->model->getTranslation('name', 'fr', false));
    }

    /** @test */
    public function it_must_return_empty_string_when_attribute_does_not_have_translation_and_fallback_locale_is_not_translatable_and_available()
    {
        $this->setFallbackLocale('ar');

        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('', $this->model->getTranslation('name', 'fr'));
    }

    /** @test */
    public function it_must_return_empty_string_when_attribute_does_not_have_translation_and_fallback_locale_is_not_translatable_and_disabled()
    {
        $this->setFallbackLocale('ar');

        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('', $this->model->getTranslation('name', 'fr', false));
    }

    /** @test */
    public function it_can_save_multiple_translations()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        static::assertSame('Name', $this->model->name);
        static::assertSame('Nom',  $this->model->getTranslation('name', 'fr'));
        static::assertSame('Nom',  $this->model->trans('name', 'fr'));
    }

    /** @test */
    public function it_can_translate_the_attribute_with_the_current_app_locale()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        app()->setLocale('fr');

        static::assertSame('Nom', $this->model->name);

        app()->setLocale('en');

        static::assertSame('Name', $this->model->name);
    }

    /** @test */
    public function it_can_get_all_translations_for_a_specific_attribute()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        static::assertSame([
            'en' => 'Name',
            'fr' => 'Nom',
        ], $this->model->getTranslations('name'));
    }

    /** @test */
    public function it_can_get_all_locales_available_in_the_translated_attribute()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        static::assertSame(['en', 'fr'], $this->model->getTranslatedLocales('name'));
    }

    /** @test */
    public function it_can_forget_a_translation()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        static::assertSame([
            'en' => 'Name',
            'fr' => 'Nom',
        ], $this->model->getTranslations('name'));

        $this->model->forgetTranslation('name', 'en');

        static::assertSame(['fr' => 'Nom'], $this->model->getTranslations('name'));
    }

    /** @test */
    public function it_can_forget_all_translations()
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->setTranslation('slug', 'en', 'Slug en')
                    ->setTranslation('slug', 'fr', 'Slug fr')
                    ->save();

        static::assertSame([
            'en' => 'Name',
            'fr' => 'Nom',
        ], $this->model->getTranslations('name'));

        static::assertSame([
            'en' => 'slug-en',
            'fr' => 'slug-fr',
        ], $this->model->getTranslations('slug'));

        $this->model->flushTranslations('en');

        static::assertSame(['fr' => 'Nom'], $this->model->getTranslations('name'));
        static::assertSame('Nom', $this->model->getTranslation('name', 'fr'));
        static::assertSame('', $this->model->getTranslation('name', 'en'));

        static::assertSame(['fr' => 'slug-fr'], $this->model->getTranslations('slug'));
        static::assertSame('slug-fr', $this->model->getTranslation('slug', 'fr'));
        static::assertSame('', $this->model->getTranslation('slug', 'en'));
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Localization\Exceptions\UntranslatableAttributeException
     * @expectedExceptionMessage The attribute `untranslated` is untranslatable because it's not available in the translatable array: `name, slug`
     */
    public function it_will_throw_an_exception_when_trying_to_translate_an_untranslatable_attribute()
    {
        $this->model->setTranslation('untranslated', 'en', 'value');
    }

    /** @test */
    public function it_can_use_accessors_on_translated_attributes()
    {
        $this->model->setTranslation('name', 'en', 'name');

        static::assertEquals('Name', $this->model->name);
    }

    /** @test */
    public function it_can_use_mutators_on_translated_attributes()
    {
        $this->model->setTranslation('slug', 'en', 'This is a mutated slug');

        static::assertEquals('this-is-a-mutated-slug', $this->model->slug);
    }

    /** @test */
    public function it_can_set_multiple_translations_at_once()
    {
        $this->model->setTranslations('name', $translations = [
            'nl' => 'hallo',
            'en' => 'hello',
            'fr' => 'salut',
        ]);
        $this->model->save();

        static::assertEquals($translations, $this->model->getTranslations('name'));
    }

    /** @test */
    public function it_can_check_if_an_attribute_is_translatable()
    {
        static::assertTrue($this->model->isTranslatableAttribute('name'));
        static::assertFalse($this->model->isTranslatableAttribute('untranslated'));
    }

    /** @test */
    public function it_will_fire_an_event_when_a_translation_has_been_set()
    {
        $this->expectsEvents([TranslationHasBeenSet::class]);

        $this->model->setTranslation('name', 'en', 'Name');

        static::assertSame(['en' => 'Name'], $this->model->getTranslations('name'));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the fallback locale.
     *
     * @param  string  $locale
     */
    private function setFallbackLocale($locale)
    {
        $this->app['config']->set('app.fallback_locale', $locale);
    }
}
