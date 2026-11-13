<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Themes\Sixteen\Presentation\Metadata\PageDescription;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('blog.index');
middleware(PageSlugMiddleware::class);

new class extends Component
{
    public string $pageSlug = 'blog.index';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(): void
    {
        $this->pageSlug = 'blog.index';
        $this->data = [];
    }
};
?>

@php
    $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug('blog.index');
    $pageTitle = $cmsPage?->getTranslation('title', app()->getLocale(), false) ?? 'Novità';
    $pageDescription = PageDescription::resolve($cmsPage?->getAttribute('description'), app()->getLocale())
        ?? 'Notizie, comunicati e avvisi sul territorio e sui servizi civici.';
@endphp

{{-- Owner: Blog Folio. Copy/SEO: CMS JSON blog.index. Dati: ThemeComposer type=post. --}}
<x-layouts.app :title="$pageTitle" :meta-description="$pageDescription" header-active="novita">
    @volt('blog.index')
    <div class="page-content content" data-dc data-slug="blog.index" data-side="content">
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
    @endvolt
</x-layouts.app>
