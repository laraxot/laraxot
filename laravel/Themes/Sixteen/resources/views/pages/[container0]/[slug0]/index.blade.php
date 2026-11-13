<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container0.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    #[Locked]
    public string $container0 = '';
    #[Locked]
    public string $slug0 = '';

    #[Locked]
    public string $pageSlug = '';

    /** Chrome Design Comuni (JSON `layout` = design-comuni*): attiva lo scope CSS di resources/css/design-comuni. */
    #[Locked]
    public bool $isDesignComuni = false;

    /** @var array<string, mixed> */
    #[Locked]
    public array $data = [];

    public function mount(string $container0, string $slug0 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;

        $resolved = app(ResolvePageAction::class)->execute($container0, $slug0);

        $this->pageSlug = $resolved->pageSlug;
        $item = $resolved->item;
        $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug($this->pageSlug);
        // ne' un modello ne' una pagina CMS: 404 vero, non un 200 con <main> vuoto (soft-404: i motori di ricerca lo indicizzano)
        abort_if($item === null && $cmsPage === null, 404);
        // `{container0}.view` senza record (blog/events/services: slug sconosciuto o la parola `view`) e' solo il modello del dettaglio: 404, non soft-404.
        abort_if($item === null && $this->pageSlug === $container0.'.view', 404);
        $this->isDesignComuni = str_starts_with((string) $cmsPage?->layout, 'design-comuni');
        // Callers: Folio /it/blog/{slug} e /it/events/{slug} (ThemeComposer Article).
        // Nessun altro file setta data-dc sul dettaglio Article: senza questo flag il
        // body sparisce sotto CSS legacy+Filament (layout app). Schema: Article item.
        // User: «al posto di aggiungere i commenti non si vede piu' il contenuto».
        if ($item instanceof \Modules\Blog\Models\Article && \in_array($container0, ['blog', 'events'], true)) {
            $this->isDesignComuni = true;
        }

        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'slug' => $slug0,
            'item' => $item,
        ];
    }
};
?>

@php
    $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug(request()->segment(2).'.'.request()->segment(3));
    // /services/{slug} non ha una pagina per slug: il chrome è pages/services/view.json (slug view → services.view).
    if ($cmsPage === null && $container0 !== '') {
        $cmsPage = \Modules\Cms\Models\Page::findUniqueBySlug($container0.'.view');
    }
    $specialPage = $container0 === 'auth' && $slug0 === 'login'
        ? 'auth-login'
        : ($container0 === 'auth' && $slug0 === 'register'
        ? 'auth-register'
        : ($container0 === 'tickets' && $slug0 === 'track'
        ? 'ticket-track'
        : ($container0 === 'tickets' && ctype_digit($slug0) ? 'ticket-detail' : null)));
    $composer = app(\Modules\Blog\View\Composers\ThemeComposer::class);
    $blogPost = $container0 === 'blog' && $slug0 !== ''
        ? $composer->findPublishedPostBySlug($slug0)
        : ($container0 === 'events' && $slug0 !== ''
            ? $composer->findPublishedEventBySlug($slug0)
            : null);
    $pageTitle = $specialPage === 'auth-login'
        ? __('fixcity::auth.login.title.text')
        : ($specialPage === 'auth-register'
        ? __('fixcity::auth.register.title.text')
        : ($specialPage === 'ticket-track'
        ? __('fixcity::ticket.track.title')
        : ($specialPage === 'ticket-detail' ? __('pub_theme::home.map.details')
        : ($blogPost instanceof \Modules\Blog\Models\Article
            ? (string) $blogPost->getAttribute('title')
            : ($cmsPage?->getTranslation('title', app()->getLocale(), false) ?? config('app.name'))))));
    $pageDescription = match ($specialPage) {
        'auth-login' => __('fixcity::auth.login.meta_description.text'),
        'auth-register' => __('fixcity::auth.register.meta_description.text'),
        default => $blogPost instanceof \Modules\Blog\Models\Article
            ? (string) ($blogPost->getAttribute('excerpt') ?: $blogPost->getAttribute('description') ?: '')
            : \Themes\Sixteen\Presentation\Metadata\PageDescription::resolve($cmsPage?->getAttribute('description'), app()->getLocale()),
    };
    // pagine con chrome Design Comuni: asset con percorso relativo come il riferimento (DesignComuniAssets) e voce di menu attiva dal JSON
    $isDesignComuni = str_starts_with((string) ($cmsPage?->getAttribute('layout') ?? ''), 'design-comuni');
    $isDesignComuni && view()->share('dcRelativeAssets', true);
    $headerActive = $isDesignComuni ? (string) data_get($cmsPage?->content_blocks, '0.data.header_active', '') : '';
    // `layout` del JSON: `test` usa la shell di parità; ogni pagina reale usa la shell canonica app.
    $jsonLayout = $cmsPage?->getAttribute('layout') ?? 'app';
    $layoutComponent = $jsonLayout === 'test'
        ? 'layouts.test'
        : 'layouts.app';
    // dettaglio pubblico di una segnalazione (DCP-148): dati dall'Action (404 se non esiste o non e' pubblica), chrome Design Comuni come /it/tickets
    $ticketDetail = null;
    if ($specialPage === 'ticket-detail') {
        $ticketDetail = app(\Modules\Fixcity\Actions\BuildPublicTicketDetailAction::class)->execute((int) $slug0, auth()->user());
        abort_if($ticketDetail === null, 404);
        $pageTitle = $ticketDetail['title'];
        $pageDescription = $ticketDetail['meta_description'];
        $isDesignComuni = true;
        view()->share('dcRelativeAssets', true);
        $headerActive = 'all-services';
        $layoutComponent = 'layouts.app';
    }
    // track: stesso telaio DC dell'elenco (B-11 audit UI/UX) — data-dc + shell app
    if ($specialPage === 'ticket-track') {
        $isDesignComuni = true;
        view()->share('dcRelativeAssets', true);
        $headerActive = 'track';
        $layoutComponent = 'layouts.app';
    }
    // dettaglio Novità/Eventi (Article): shell app + data-dc.
    // Il corpo lo monta il JSON `pages/{blog|events}/view.json` (slug `view` → `blog.view` / `events.view`).
    if ($blogPost instanceof \Modules\Blog\Models\Article) {
        $isDesignComuni = true;
        view()->share('dcRelativeAssets', true);
        $headerActive = $container0 === 'events' ? 'live' : 'news';
        $layoutComponent = 'layouts.app';
    }
@endphp
<x-dynamic-component :component="$layoutComponent" :title="$pageTitle" :meta-description="$pageDescription" :header-active="$headerActive">
    @if ($specialPage !== null)
        <div class="page-content content"@if(in_array($specialPage, ['ticket-detail', 'ticket-track', 'auth-login', 'auth-register'], true)) data-dc @endif data-slug="{{ request()->segment(2).'.'.request()->segment(3) }}" data-side="content">
            @if ($specialPage === 'auth-login')
                @include('pub_theme::components.pages.auth-login')
            @elseif ($specialPage === 'auth-register')
                @include('pub_theme::components.pages.auth-register')
            @elseif ($specialPage === 'ticket-track')
                @include('pub_theme::components.pages.ticket-track')
            @else
                @include('pub_theme::components.pages.ticket-detail')
            @endif
        </div>
    @else
        {{--
            Il div.page-content e' la radice del componente Volt. Livewire stampa wire:id sul primo tag a inizio
            riga del fragment: con <x-page> come unico contenuto finiva dentro il widget Livewire di un blocco
            (es. CreateTicketWizardWidget) e ogni wire:model / callSchemaComponentMethod colpiva il Volt.
        --}}
        @volt('container0.view')
            <div class="page-content content"@if($this->isDesignComuni) data-dc @endif data-slug="{{ $this->pageSlug }}" data-side="content">
                <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
            </div>
        @endvolt
    @endif
</x-dynamic-component>
