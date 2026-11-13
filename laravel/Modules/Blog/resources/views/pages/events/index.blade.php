<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Themes\Sixteen\Presentation\Metadata\PageDescription;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('events.index');
middleware(PageSlugMiddleware::class);

new class extends Component
{
    public string $pageSlug = 'events.index';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(): void
    {
        $this->pageSlug = 'events.index';
        $this->data = [];
    }
};
?>

@php
    $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug('events.index');
    $pageTitle = $cmsPage?->getTranslation('title', app()->getLocale(), false) ?? 'Eventi';
    $pageDescription = PageDescription::resolve($cmsPage?->getAttribute('description'), app()->getLocale())
        ?? 'Appuntamenti pubblici, laboratori e incontri sul territorio.';
@endphp

{{-- Owner: Blog Folio. Copy/SEO: CMS JSON events.index. Dati: ThemeComposer type=event. --}}
<x-layouts.app :title="$pageTitle" :meta-description="$pageDescription" header-active="live">
    @volt('events.index')
    <div class="page-content content" data-dc data-slug="events.index" data-side="content">
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
    @endvolt
</x-layouts.app>
