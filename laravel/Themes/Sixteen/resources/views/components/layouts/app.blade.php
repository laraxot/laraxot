@props(['title' => '', 'metaDescription' => '', 'pageShell' => false, 'bodyPage' => ''])

<x-layouts.main :title="$title" :description="$metaDescription" :body-page="$bodyPage">
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">{{ __('pub_theme::ui.skip_to_content') }}</a>
        <a class="visually-hidden-focusable" href="#footer">{{ __('pub_theme::ui.skip_to_footer') }}</a>
    </div><!-- /skiplink -->

    <x-section slug="header" />
    {{-- 
    <x-breadcrumbs />
    --}}
    
    <main id="main-container" tabindex="-1">
        {{ $slot }}
    </main>

    @include('pub_theme::components.sections.search-modal')

    <x-section slug="footer" tpl="full" />
</x-layouts.main>
