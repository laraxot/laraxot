{{-- Bootstrap Italia Skiplinks Component --}}

<div class="skiplinks" role="navigation" aria-label="{{ __('pub_theme::bootstrap_italia.skiplinks.skip_links.label') }}">
    <a class="visually-hidden-focusable screen-reader screen-reader-focusable" href="#content">
        {{ __('pub_theme::bootstrap_italia.skiplinks.skip_to_content.label') }}
    </a>
    <a class="visually-hidden-focusable screen-reader screen-reader-focusable" href="#navigation">
        {{ __('pub_theme::bootstrap_italia.skiplinks.skip_to_the_navigation_menu.label') }}
    </a>
    <span class="screen-reader screen-reader-text">{{ __('pub_theme::bootstrap_italia.skiplinks.screen_reader.label') }}</span>
    @if(!empty($links))
        @foreach($links as $link)
        <a class="visually-hidden-focusable screen-reader screen-reader-focusable"
           href="{{ $link['href'] }}">
            {{ $link['label'] }}
        </a>
        @endforeach
    @endif
</div>
