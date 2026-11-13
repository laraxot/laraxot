<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container0.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';

    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        $this->pageSlug = $container0.'.index';
        $this->data = ['container0' => $container0];
    }
};
?>

@php
    $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug(request()->segment(2).'.index');
    $utilityPage = match ($container0) {
        'domande-frequenti' => 'faq',
        'mappa-sito' => 'sitemap',
        default => null,
    };
    abort_if($cmsPage === null && $utilityPage === null && $container0 !== 'lista-categorie', 404);
    $pageTitle = match (true) {
        $container0 === 'lista-categorie' => __('pub_theme::services.categories.title'),
        $utilityPage === 'faq' => __('pub_theme::faq.meta.title'),
        $utilityPage === 'sitemap' => __('pub_theme::sitemap.meta.title'),
        default => $cmsPage?->getTranslation('title', app()->getLocale(), false) ?? config('app.name'),
    };
    $pageDescription = match (true) {
        $container0 === 'lista-categorie' => __('pub_theme::services.categories.subtitle'),
        $utilityPage === 'faq' => __('pub_theme::faq.meta.description'),
        $utilityPage === 'sitemap' => __('pub_theme::sitemap.meta.description'),
        default => $cmsPage?->getAttribute('description') ?? '',
    };
@endphp
<x-layouts.app :title="$pageTitle" :meta-description="$pageDescription">
    @volt('pages-old.container0.index')
    <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
        @if ($container0 === 'lista-categorie')
            @include('pub_theme::pages.services.categories-content')
        @elseif ($container0 === 'domande-frequenti')
            @include('pub_theme::components.pages.faq')
        @elseif ($container0 === 'mappa-sito')
            @include('pub_theme::components.pages.sitemap')
        @else
            <x-page side="content" :slug="$pageSlug" :data="$data" />
        @endif
    </div>
    @endvolt
</x-layouts.app>
