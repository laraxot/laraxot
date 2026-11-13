<x-layouts.main>
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">{{ __('pub_theme::ui.skip_to_content') }}</a>
        <a class="visually-hidden-focusable" href="#footer">{{ __('pub_theme::ui.skip_to_footer') }}</a>
    </div><!-- /skiplink -->

    <x-section slug="header" />

    <main id="main-container" tabindex="-1" @if(!empty($bodyPage)) data-page="{{ $bodyPage }}" @endif>
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    <x-section slug="footer" tpl="full" />
</x-layouts.main>
