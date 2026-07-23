<?php

use function Laravel\Folio\{middleware, name};
use Modules\Cms\Models\Page as CmsPage;

/** @var array $base_middleware */
$base_middleware = [];

name('home');
middleware($base_middleware);
?>

@php
    $homePage = CmsPage::query()->where('slug', 'home')->first();
    $homeTitle = (string) __('fixcity::ticket.heading.title.label');
    if ($homePage !== null) {
        $translatedHomeTitle = $homePage->getTranslation('title', app()->getLocale());
        if (is_string($translatedHomeTitle) && $translatedHomeTitle !== '') {
            $homeTitle = $translatedHomeTitle;
        }
    }
@endphp

@php
    $mapLitPreload = null;
    $manifestPath = base_path('Themes/Sixteen/public/manifest.json');
    if (is_readable($manifestPath)) {
        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        $entry = is_array($manifest) ? ($manifest['../../Modules/Geo/resources/js/components/map-lit.js'] ?? null) : null;
        $mapLitPreload = is_array($entry) ? ($entry['file'] ?? null) : null;
    }
@endphp

@if ($mapLitPreload)
    @push('styles')
        <link rel="modulepreload" href="{{ asset('themes/Sixteen/'.$mapLitPreload) }}" crossorigin>
    @endpush
@endif

<x-layouts.app :title="$homeTitle" :meta-description="__('sixteen::home.meta.description')" body-page="ticket-list">
    <x-page side="content" slug="home" />
</x-layouts.app>
