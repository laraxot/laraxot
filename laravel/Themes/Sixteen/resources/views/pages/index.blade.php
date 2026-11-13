<?php

declare(strict_types=1);

use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Themes\Sixteen\Presentation\Metadata\PageDescription;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('home');
middleware(PageSlugMiddleware::class);

new class extends Component {
    #[Locked]
    public string $pageSlug = 'home';

    /** @var array<string, mixed> */
    #[Locked]
    public array $data = [];

    public function mount(): void
    {
        $this->pageSlug = 'home';
        $this->data = [];
    }
};
?>

@php
    $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug('home');
    $pageTitle = $cmsPage?->getTranslation('title', app()->getLocale(), false) ?? 'FixCity';
    $pageDescription = PageDescription::resolve($cmsPage?->getAttribute('description'), app()->getLocale())
        ?? 'Servizi digitali per segnalare e seguire i problemi del territorio.';
@endphp

{{--
    The homepage is a CMS composition, not a second hard-coded site.
    Filament/CMS owns localized content and reusable Sixteen blocks own presentation.
    Keeping this Folio entry point intentionally small prevents the homepage and
    the generic `home` page from drifting apart.
--}}
<x-layouts.app
    :title="$pageTitle"
    :meta-description="$pageDescription"
    body-page="homepage"
>
    @volt('home')
        <div class="page-content content fc-home" data-dc data-slug="home" data-side="content">
            <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
        </div>
    @endvolt
</x-layouts.app>
