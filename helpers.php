<?php

if ( ! function_exists('localization')) {
    /**
     * Get the Localization instance.
     *
     * @return Arcanedev\Localization\Contracts\Localization
     */
    function localization()
    {
        return app(Arcanedev\Localization\Contracts\Localization::class);
    }
}
