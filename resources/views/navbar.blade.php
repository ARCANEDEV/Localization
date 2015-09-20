<ul class="navbar-locales">
    @foreach($supportedLocales as $key => $locale)
        <li class="{{ localization()->getCurrentLocale() == $key ? 'active' : '' }}">
            <a href="{{ localization()->getLocalizedURL($key) }}" rel="alternate" hreflang="{{ $key  }}">
                {{ $locale->native() }}
            </a>
        </li>
    @endforeach
</ul>
