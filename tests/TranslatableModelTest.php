<?php

declare(strict_types=1);

namespace Arcanedev\Localization\Tests;

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

    public function setUp(): void
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
    public function it_can_set_translated_values_when_creating_a_model(): void
    {
        /** @var  \Arcanedev\Localization\Tests\Stubs\Models\TranslatableModel  $model */
        $model = TranslatableModel::query()->create([
            'name' => [
                'en' => 'Name',
            ],
        ]);

        static::assertSame('Name', $model->name);
    }

    /** @test */
    public function it_can_save_a_translated_attribute(): void
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('Name', $this->model->name);
    }

    /** @test */
    public function it_can_translate_attribute_with_a_fallback_locale(): void
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        $expected = 'Name';

        static::assertSame($expected, $this->model->getTranslation('name', 'fr'));
        static::assertSame($expected, $this->model->trans('name', 'fr'));
    }

    /** @test */
    public function it_must_return_empty_string_when_attribute_does_not_have_translation_and_fallback_locale_is_disabled(): void
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('', $this->model->getTranslation('name', 'fr', false));
    }

    /** @test */
    public function it_must_return_empty_string_when_attribute_does_not_have_translation_and_fallback_locale_is_not_translatable_and_available(): void
    {
        $this->setFallbackLocale('ar');

        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('', $this->model->getTranslation('name', 'fr'));
    }

    /** @test */
    public function it_must_return_empty_string_when_attribute_does_not_have_translation_and_fallback_locale_is_not_translatable_and_disabled(): void
    {
        $this->setFallbackLocale('ar');

        $this->model->setTranslation('name', 'en', 'Name')
                    ->save();

        static::assertSame('', $this->model->getTranslation('name', 'fr', false));
    }

    /** @test */
    public function it_can_save_multiple_translations(): void
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        static::assertSame('Name', $this->model->name);
        static::assertSame('Nom',  $this->model->getTranslation('name', 'fr'));
        static::assertSame('Nom',  $this->model->trans('name', 'fr'));
    }

    /** @test */
    public function it_can_translate_the_attribute_with_the_current_app_locale(): void
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
    public function it_can_get_all_translations_for_a_specific_attribute(): void
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
    public function it_handle_null_value_from_database(): void
    {
        $testModel = (new class() extends TranslatableModel {
            public function setAttributesExternally(array $attributes)
            {
                $this->attributes = $attributes;
            }
        });

        $testModel->setAttributesExternally([
            'name'        => json_encode(null),
            'other_field' => null,
        ]);

        $this->assertEquals('', $testModel->name);
        $this->assertEquals('', $testModel->other_field);
    }

    /** @test */
    public function it_can_get_all_translations(): void
    {
        $translations = ['fr' => 'Salut', 'en' => 'Hello'];

        $this->model->setTranslations('name', $translations)
                    ->setTranslations('slug', $translations)
                    ->save();

        static::assertEquals([
            'name' => ['fr' => 'Salut', 'en' => 'Hello'],
            'slug' => ['fr' => 'salut', 'en' => 'hello'],
        ], $this->model->translations);
    }

    /** @test */
    public function it_can_get_all_translations_for_all_translatable_attributes(): void
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->setTranslation('slug', 'en', 'Name')
                    ->setTranslation('slug', 'fr', 'Nom')
                    ->save();

        static::assertSame([
            'name' => [
                'en' => 'Name',
                'fr' => 'Nom',
            ],
            'slug' => [
                'en' => 'name',
                'fr' => 'nom',
            ],
        ], $this->model->getTranslations());
    }

    /** @test */
    public function it_can_get_all_locales_available_in_the_translated_attribute(): void
    {
        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'fr', 'Nom')
                    ->save();

        static::assertSame(['en', 'fr'], $this->model->getTranslatedLocales('name'));
    }

    /** @test */
    public function it_can_forget_a_translation(): void
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
    public function it_can_forget_all_translations(): void
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

    /** @test */
    public function it_will_throw_an_exception_when_trying_to_translate_an_untranslatable_attribute(): void
    {
        $this->expectException(\Arcanedev\Localization\Exceptions\UntranslatableAttributeException::class);
        $this->expectExceptionMessage("The attribute `untranslated` is untranslatable because it's not available in the translatable array: `name, slug`");

        $this->model->setTranslation('untranslated', 'en', 'value');
    }

    /** @test */
    public function it_can_use_accessors_on_translated_attributes(): void
    {
        $this->model->setTranslation('name', 'en', 'name');

        static::assertEquals('Name', $this->model->name);
    }

    /** @test */
    public function it_can_use_mutators_on_translated_attributes(): void
    {
        $this->model->setTranslation('slug', 'en', 'This is a mutated slug');

        static::assertEquals('this-is-a-mutated-slug', $this->model->slug);
    }

    /** @test */
    public function it_can_set_multiple_translations_at_once(): void
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
    public function it_can_check_if_an_attribute_is_translatable(): void
    {
        static::assertTrue($this->model->isTranslatableAttribute('name'));
        static::assertFalse($this->model->isTranslatableAttribute('untranslated'));
    }

    /** @test */
    public function it_can_check_if_an_attribute_has_translation(): void
    {
        $this->model->setTranslation('name', 'en', 'Hello')
                    ->setTranslation('name', 'fr', 'Salut')
                    ->setTranslation('name', 'nl', null)
                    ->save();

        static::assertTrue($this->model->hasTranslation('name', 'en'));
        static::assertTrue($this->model->hasTranslation('name', 'fr'));

        static::assertFalse($this->model->hasTranslation('name', 'nl'));
        static::assertFalse($this->model->hasTranslation('name', 'ar'));
    }

    /** @test */
    public function it_will_fire_an_event_when_a_translation_has_been_set(): void
    {
        $this->expectsEvents([TranslationHasBeenSet::class]);

        $this->model->setTranslation('name', 'en', 'Name');

        static::assertSame(['en' => 'Name'], $this->model->getTranslations('name'));
    }

    /** @test */
    public function it_will_return_fallback_locale_translation_when_getting_an_empty_translation_from_the_locale(): void
    {
        $this->setFallbackLocale('en');

        $this->model->setTranslation('name', 'en', 'Name')
                    ->setTranslation('name', 'nl', null)
                    ->save();

        static::assertSame('Name', $this->model->getTranslation('name', 'nl'));
    }

    /** @test */
    public function it_will_return_correct_translation_value_if_value_is_set_to_zero(): void
    {
        $this->model->setTranslation('name', 'en', '0')
                    ->save();

        static::assertSame('0', $this->model->getTranslation('name', 'en'));
    }

    /** @test */
    public function it_will_not_return_fallback_value_if_value_is_set_to_zero(): void
    {
        $this->setFallbackLocale('en');

        $this->model->setTranslation('name', 'en', '1')
                    ->setTranslation('name', 'fr', '0')
                    ->save();

        static::assertSame('0', $this->model->getTranslation('name', 'fr'));
    }

    /** @test */
    public function it_will_not_remove_zero_value_of_other_locale_in_database(): void
    {
        $this->setFallbackLocale('en');

        $this->model->setTranslation('name', 'en', '0')
                    ->setTranslation('name', 'fr', '1')
                    ->save();

        static::assertSame('0', $this->model->getTranslation('name', 'en'));
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
    private function setFallbackLocale($locale): void
    {
        $this->app['config']->set('app.fallback_locale', $locale);
    }
}
