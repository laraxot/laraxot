@php
    try {
        /** @var array<string, array<string, mixed>> $supportedLocales */
        $supportedLocales = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLocales();
    } catch (\Throwable) {
        $supportedLocales = [];
    }

    if ($supportedLocales === []) {
        $supportedLocales = [
            'it' => ['native' => 'Italiano'],
            'en' => ['native' => 'English'],
        ];
    }

    $currentLocale = app()->getLocale();
@endphp
<div class="nav-item dropdown it-language-switcher">
    <button
        type="button"
        class="nav-link dropdown-toggle"
        data-bs-toggle="dropdown"
        data-bs-display="static"
        aria-expanded="false"
        aria-haspopup="true"
        aria-controls="languages"
    >
        <span class="visually-hidden">{{ __('pub_theme::header.language.active_prefix.label') }}</span>
        <span>
            @php
                $currentShort = match (strtolower(substr($currentLocale, 0, 2))) {
                    'it' => __('pub_theme::header.language.code_it.label'),
                    'en' => __('pub_theme::header.language.code_en.label'),
                    default => strtoupper(substr($currentLocale, 0, 2)),
                };
            @endphp
            {{ $currentShort }}
        </span>
        <svg class="icon">
            <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
        </svg>
    </button>
    <div class="dropdown-menu" id="languages" role="menu">
        @foreach ($supportedLocales as $localeCode => $_meta)
            @php
                $localeKey = strtolower((string) $localeCode);
                $isCurrent = str_starts_with(strtolower($currentLocale), $localeKey)
                    || strtolower($currentLocale) === $localeKey;
                $short = match (substr($localeKey, 0, 2)) {
                    'it' => __('pub_theme::header.language.code_it.label'),
                    'en' => __('pub_theme::header.language.code_en.label'),
                    default => strtoupper(substr($localeKey, 0, 3)),
                };
                try {
                    $href = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($localeCode);
                } catch (\Throwable) {
                    $href = url('/'.$localeKey);
                }
            @endphp
            <a
                class="dropdown-item"
                href="{{ $href }}"
                role="menuitem"
                @if ($isCurrent) aria-current="true" @endif
            >
                {{ $short }}
                @if ($isCurrent)
                    <span class="visually-hidden">{{ __('pub_theme::header.language.selected_suffix.label') }}</span>
                @endif
            </a>
        @endforeach
    </div>
</div>
