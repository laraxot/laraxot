@props(['data' => []])

@php
    use Illuminate\Support\Str;
    use Modules\Fixcity\Enums\TicketStatusEnum;
    use Modules\Fixcity\ViewModels\SegnalazioniFilterViewModel;

    $ns = 'pub_theme::segnalazione';
    $blockData = is_array($data) ? $data : [];
    $phoneNumber = (string) ($blockData['phone'] ?? '05 0505');
    $t = function (mixed $value, string $default = '') use ($phoneNumber): string {
        if (empty($value)) return $default;
        $resolved = str_contains((string) $value, '::') ? __((string) $value) : (string) $value;
        return str_replace(':phone', $phoneNumber, $resolved);
    };

    $breadcrumbItems = collect($blockData['breadcrumb'] ?? [])->map(fn ($item): array => [
        'label' => $t($item['label'] ?? ''), 'url' => $item['url'] ?? null, 'active' => $item['active'] ?? false,
    ])->all();
    $title = $t($blockData['title'] ?? '', __($ns.'.elenco.title'));
    $subtitle = $t($blockData['subtitle'] ?? '', __($ns.'.elenco.subtitle', ['count' => 73]));
    $tabs = collect($blockData['tabs']['items'] ?? [])->map(fn ($tab): array => [
        'id' => $tab['id'] ?? 'map', 'label' => $t($tab['label'] ?? ''), 'active' => $tab['active'] ?? false,
    ])->values()->all();
    $mainContent = $blockData['main_content'] ?? [];
    $selectedTypes = request()->collect('types')->filter(static fn ($type): bool => is_string($type) && $type !== '')->values()->all();

    $baseTicketsQuery = \Modules\Fixcity\Models\Ticket::query();
    $currentUserId = auth()->id();
    if ($currentUserId !== null) {
        $baseTicketsQuery->where(function ($query) use ($currentUserId): void {
            $query->whereIn('status', TicketStatusEnum::canViewByAll())->orWhere('owner_id', $currentUserId);
        });
    } else {
        $baseTicketsQuery->whereIn('status', TicketStatusEnum::canViewByAll());
    }

    $filterViewModel = new SegnalazioniFilterViewModel();
    $filters = [
        'title' => $t($blockData['filters']['title'] ?? '', __($ns.'.filters.legend.label')),
        'items' => $filterViewModel->getFilterItems(), 'total' => $filterViewModel->getTotalCount(),
    ];

    $filteredTicketsQuery = clone $baseTicketsQuery;
    if ($selectedTypes !== []) $filteredTicketsQuery->whereIn('type', $selectedTypes);
    $visibleTotal = (clone $filteredTicketsQuery)->count();
    // `media` in una query sola (DB-13); le foto escono da BuildTicketPublicImageUrlsAction: solo immagini, mai PDF (SEC-04).
    $liveTickets = $filteredTicketsQuery->with('media')->latest()->take(20)->get();
    $ticketImageUrls = app(\Modules\Fixcity\Actions\BuildTicketPublicImageUrlsAction::class);
    $resultsCount = $filterViewModel->getFilteredCount($selectedTypes);
    $initialOffset = $liveTickets->count();
    $hasMore = $visibleTotal > $initialOffset;

    $filterItemsAttr = e(json_encode($filters['items'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
    $selectedTypesAttr = e(json_encode($selectedTypes, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
    $cta = ($mainContent['cta'] ?? []) !== [] ? [
        'title' => $t($mainContent['cta']['title'] ?? '', __($ns.'.map.cta.title.label')),
        'text' => $t($mainContent['cta']['text'] ?? '', __($ns.'.map.cta.text.label')),
        'button_text' => $t($mainContent['cta']['button_text'] ?? '', __($ns.'.map.cta.button.label')),
    ] : [];

    $contactsData = $blockData['contacts'] ?? [];
    $contacts = $contactsData !== [] ? [
        'contact_title' => $t($contactsData['contact_title'] ?? '', __($ns.'.contacts.title')),
        'contacts' => collect($contactsData['contacts'] ?? [])->map(fn ($contact): array => ['label' => $t($contact['label'] ?? ''), 'url' => $contact['url'] ?? '#', 'icon' => $contact['icon'] ?? 'it-files'])->all(),
    ] : [];

    $tabsSectionId = $blockData['tabs']['id'] ?? 'map-and-list';
    $filtersSectionId = $mainContent['id'] ?? 'filter-and-cards';
    // Senza lingua: con il prefisso (/it/api/...) la rotta risponde HTML, non JSON (ApiUrlsAreNotLocalizedTest).
    $listApiUrl = '/api/tickets/list';
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';

    $contactLinks = [
        'faq' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::faqIndex(), 'icon' => 'it-files'],
        'assistance' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsCreate(), 'icon' => 'it-mail'],
        'phone' => ['url' => 'tel:'.str_replace(' ', '', $phoneNumber), 'icon' => 'it-phone'],
        'appointment' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::servicesIndex(), 'icon' => 'it-calendar'],
    ];
@endphp

<div
    id="segnalazioni-elenco-root"
    role="region"
    aria-label="{{ $title }}"
    data-section-map="{{ $tabsSectionId }}"
    data-section-filters="{{ $filtersSectionId }}"
    x-data="{
        activeTab: '{{ $tabs[0]['id'] ?? 'map' }}',
        filterOpen: false,
        offset: {{ $initialOffset }},
        hasMore: {{ $hasMore ? 'true' : 'false' }},
        loading: false,
        extraTickets: [],
        listUrl: '{{ $listApiUrl }}',
        loadMore() {
            if (this.loading || !this.hasMore) return;
            this.loading = true;
            const params = new URLSearchParams();
            document.querySelectorAll('map-filter-lit input[data-type]:checked').forEach((input) => params.append('types[]', input.dataset.type || ''));
            params.append('offset', String(this.offset));
            fetch(this.listUrl + '?' + params.toString())
                .then((response) => (response.ok ? response.json() : Promise.reject(new Error('load failed'))))
                .then((data) => {
                    const tickets = Array.isArray(data.tickets) ? data.tickets : [];
                    this.extraTickets = this.extraTickets.concat(tickets);
                    this.offset += tickets.length;
                    this.hasMore = Boolean(data.has_more) && tickets.length > 0;
                })
                .catch(() => { this.hasMore = false; })
                .finally(() => { this.loading = false; });
        },
        clearFilters() {
            document.querySelectorAll('map-filter-lit input[data-type]:checked').forEach((input) => {
                input.checked = false;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        }
    }"
>
    <div class="container" id="main-container">
        <div class="row justify-content-center mb-md-40 mb-lg-80">
            <div class="col-12 col-lg-10">
                <div class="cmp-breadcrumbs" role="navigation">
                    <nav class="breadcrumb-container" aria-label="{{ __('fixcity::global.breadcrumb') }}">
                        <ol class="breadcrumb p-0" data-element="breadcrumb">
                            @foreach ($breadcrumbItems as $item)
                                <li class="breadcrumb-item{{ $item['active'] ? ' active' : '' }}"{{ $item['active'] ? ' aria-current="page"' : '' }}>
                                    @if ($item['url'])
                                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a><span class="separator">/</span>
                                    @else
                                        {{ $item['label'] }}
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
                <div class="cmp-heading p-0">
                    <h1 class="title-xxxlarge">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="subtitle-small">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            <hr class="d-none d-lg-block mt-30 mb-2">
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-3 d-none d-lg-block" id="{{ $filtersSectionId }}">
                <fieldset>
                    <legend class="h6 text-uppercase category-list__title">{{ $filters['title'] }}</legend>
                    <map-filter-lit
                        map-id="ticket-map"
                        title=""
                        filters="{{ $filterItemsAttr }}"
                        total="{{ $filters['total'] }}"
                        initial-selected="{{ $selectedTypesAttr }}"
                        results-target="#segnalazioni-results-count"
                        clear-target="#segnalazioni-clear-filters"
                    ></map-filter-lit>
                </fieldset>
            </div>

            <div class="col-lg-8 offset-lg-1" aria-labelledby="segnalazioni-results-count">
                <div class="d-flex justify-content-between border-bottom border-light pb-3 mt-5">
                    <span class="search-results" id="segnalazioni-results-count" data-count-template="{{ __($ns.'.results.count.text', ['count' => ':count']) }}">{{ __($ns.'.results.count.text', ['count' => $resultsCount]) }}</span>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn p-0 pe-2 d-lg-none" @click="filterOpen = !filterOpen" :aria-expanded="filterOpen.toString()" aria-controls="{{ $filtersSectionId }}">
                            <span class="rounded-icon">
                                <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-funnel"></use></svg>
                            </span>
                            <span class="t-primary title-xsmall-semi-bold ms-1">{{ __($ns.'.filter.button.label') }}</span>
                        </button>
                        <button type="button" id="segnalazioni-clear-filters" class="btn p-0 pe-2 d-none d-lg-block" @click="clearFilters()">
                            <span class="title-xsmall-semi-bold ms-1">{{ __($ns.'.filter.remove.label') }}</span>
                        </button>
                    </div>
                </div>

                @if (!empty($tabs))
                    <ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none" id="tabDisservizio" role="tablist" data-section="{{ $tabsSectionId }}">
                        @foreach ($tabs as $index => $tab)
                            <li class="nav-item w-100" role="tab">
                                <a
                                    class="nav-link title-medium-semi-bold pt-0"
                                    :class="activeTab === '{{ $tab['id'] }}' ? 'active' : ''"
                                    href="#data-ex-disservizio{{ $index + 1 }}"
                                    @click.prevent="activeTab = '{{ $tab['id'] }}'"
                                    role="button"
                                    aria-controls="disservizio{{ $index + 1 }}"
                                    :aria-selected="activeTab === '{{ $tab['id'] }}' ? 'true' : 'false'"
                                >{{ $tab['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="tab-content">
                    <div class="tab-pane fade" :class="activeTab === '{{ $tabs[0]['id'] ?? 'map' }}' ? 'show active' : ''" id="data-ex-disservizio1" role="tabpanel" x-show="activeTab === '{{ $tabs[0]['id'] ?? 'map' }}'" x-cloak>
                        <div class="row">
                            <div class="col-12">
                                <div class="map-box segnalazioni-map overflow-hidden">
                                    <map-lit
                                        id="ticket-map"
                                        data-url="/data/tickets.json"
                                        lat="43.7696"
                                        lng="11.2558"
                                        height="clamp(11.5rem,52vw,46rem)"
                                        class="d-block w-100"
                                        style="height:clamp(11.5rem,52vw,46rem)"
                                        aria-label="{{ __($ns.'.map.image.alt') }}"
                                    ></map-lit>
                                </div>
                            </div>
                            @if (!empty($cta))
                                <div class="col-lg-6 mt-50 mb-4 mb-lg-0">
                                    <div class="cmp-text-button mt-0">
                                        <h2 class="title-xxlarge mb-0">{{ $cta['title'] }}</h2>
                                        <div class="text-wrapper">
                                            <p class="subtitle-small mb-3 mt-3">{{ $cta['text'] }}</p>
                                        </div>
                                        <div class="button-wrapper">
                                            <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::ticketsCreate() }}" class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0">
                                                <span>{{ $cta['button_text'] }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" :class="activeTab !== '{{ $tabs[0]['id'] ?? 'map' }}' ? 'show active' : ''" id="data-ex-disservizio2" role="tabpanel" x-show="activeTab !== '{{ $tabs[0]['id'] ?? 'map' }}'" x-cloak>
                        <div class="row">
                            @forelse ($liveTickets as $item)
                                @php
                                    $itemLocation = is_array($item->location) ? $item->location : [];
                                    $itemAddress = (string) ($itemLocation['address'] ?? $itemLocation['display_name'] ?? '');
                                    $itemImages = $ticketImageUrls->execute($item, auth()->user());
                                    $collapseId = 'collapse-ticket-'.$item->getKey();
                                @endphp
                                <div class="cmp-card mb-4 mb-lg-30">
                                    <div class="card has-bkg-grey shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="cmp-info-button-card">
                                                <div class="card p-3 p-lg-4">
                                                    <div class="card-body p-0">
                                                        <h3 class="medium-title mb-0">{{ $item->name }}</h3>
                                                        <p class="card-info">{{ __($ns.'.card.type.label') }}<br><span>{{ $item->type_label }}</span></p>
                                                        <div class="accordion-item">
                                                            <div class="accordion-header">
                                                                <button class="collapsed accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                                                                    <span class="d-flex align-items-center">
                                                                        {{ __($ns.'.card.expand.button.label') }}
                                                                        <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-expand"></use></svg>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                            <div id="{{ $collapseId }}" class="accordion-collapse collapse pb-0" role="region">
                                                                <div class="accordion-body p-0">
                                                                    <div class="cmp-info-summary bg-white border-0">
                                                                        <div class="card">
                                                                            <div class="card-header border-bottom border-light p-0 mb-0 d-flex justify-content-end">
                                                                                <a href="#" class="d-none text-decoration-none"><span class="text-button-sm-semi t-primary">{{ $item->isOwnedByAuthenticatedUser() ? __('pub_theme::components_common.actions.edit.label') : '' }}</span></a>
                                                                            </div>
                                                                            <div class="card-body p-0">
                                                                                @if ($itemAddress !== '')
                                                                                    <div class="single-line-info border-light">
                                                                                        <div class="text-paragraph-small">{{ __($ns.'.card.address.label') }}</div>
                                                                                        <div class="border-light"><p class="data-text">{{ $itemAddress }}</p></div>
                                                                                    </div>
                                                                                @endif
                                                                                @if ($item->content)
                                                                                    <div class="single-line-info border-light">
                                                                                        <div class="text-paragraph-small">{{ __($ns.'.card.detail.label') }}</div>
                                                                                        <div class="border-light"><p class="data-text">{{ Str::limit((string) $item->content, 200) }}</p></div>
                                                                                    </div>
                                                                                @endif
                                                                                <div class="single-line-info border-light">
                                                                                    <div class="text-paragraph-small">{{ __($ns.'.card.images.label') }}</div>
                                                                                    <div class="border-light border-0">
                                                                                        @if ($itemImages !== [])
                                                                                            <div class="d-lg-flex gap-2 mt-3">
                                                                                                @foreach ($itemImages as $imageUrl)
                                                                                                    <div>
                                                                                                        <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="img-fluid w-100 mb-3 mb-lg-0">
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @else
                                                                                            <p class="data-text">{{ __($ns.'.card.no_images.label') }}</p>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-footer p-0 d-none"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="col-12" role="status">{{ __($ns.'.results.empty') }}</p>
                            @endforelse

                            <template x-for="ticket in extraTickets" :key="ticket.id">
                                <div class="cmp-card mb-4 mb-lg-30">
                                    <div class="card has-bkg-grey shadow-sm">
                                        <div class="card-body p-0">
                                            <div class="cmp-info-button-card">
                                                <div class="card p-3 p-lg-4">
                                                    <div class="card-body p-0">
                                                        <h3 class="medium-title mb-0" x-text="ticket.name"></h3>
                                                        <p class="card-info">{{ __($ns.'.card.type.label') }}<br><span x-text="ticket.type_label"></span></p>
                                                        <div class="accordion-item">
                                                            <div class="accordion-header">
                                                                <button class="collapsed accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse-extra-' + ticket.id" :aria-controls="'collapse-extra-' + ticket.id" aria-expanded="false">
                                                                    <span class="d-flex align-items-center">
                                                                        {{ __($ns.'.card.expand.button.label') }}
                                                                        <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $sprite }}#it-expand"></use></svg>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                            <div :id="'collapse-extra-' + ticket.id" class="accordion-collapse collapse pb-0" role="region">
                                                                <div class="accordion-body p-0">
                                                                    <div class="cmp-info-summary bg-white border-0">
                                                                        <div class="card">
                                                                            <div class="card-header border-bottom border-light p-0 mb-0 d-flex justify-content-end">
                                                                                <a href="#" class="d-none text-decoration-none"><span class="text-button-sm-semi t-primary"></span></a>
                                                                            </div>
                                                                            <div class="card-body p-0">
                                                                                <div class="single-line-info border-light" x-show="ticket.address">
                                                                                    <div class="text-paragraph-small">{{ __($ns.'.card.address.label') }}</div>
                                                                                    <div class="border-light"><p class="data-text" x-text="ticket.address"></p></div>
                                                                                </div>
                                                                                <div class="single-line-info border-light">
                                                                                    <div class="text-paragraph-small">{{ __($ns.'.card.detail.label') }}</div>
                                                                                    <div class="border-light"><p class="data-text" x-text="ticket.content"></p></div>
                                                                                </div>
                                                                                <div class="single-line-info border-light">
                                                                                    <div class="text-paragraph-small">{{ __($ns.'.card.images.label') }}</div>
                                                                                    <div class="border-light border-0">
                                                                                        <template x-if="ticket.images && ticket.images.length > 0">
                                                                                            <div class="d-lg-flex gap-2 mt-3">
                                                                                                <template x-for="image in ticket.images" :key="image">
                                                                                                    <div><img :src="image" :alt="ticket.name" class="img-fluid w-100 mb-3 mb-lg-0"></div>
                                                                                                </template>
                                                                                            </div>
                                                                                        </template>
                                                                                        <p class="data-text" x-show="!ticket.images || ticket.images.length === 0">{{ __($ns.'.card.no_images.label') }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-footer p-0 d-none"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="col-12 text-center" x-show="hasMore">
                                <button type="button" id="segnalazioni-load-more" class="btn btn-outline-primary mobile-full py-3 mt-10 mx-auto" @click="loadMore()" :disabled="loading">
                                    <span x-text="loading ? '...' : '{{ __($ns.'.elenco.load_more') }}'">{{ __($ns.'.elenco.load_more') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pub_theme::components.blocks.feedback.rating', ['data' => $blockData['rating'] ?? []])

    @if (!empty($contacts))
        <section id="info-contacts" class="p-contacts" aria-labelledby="contacts-title">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-lg-5">
                    <div class="cmp-contacts">
                        <div class="card w-100">
                            <div class="card-body">
                                <h2 class="title-medium-2-semi-bold" id="contacts-title">{{ $contacts['contact_title'] }}</h2>
                                <ul class="contact-list p-0">
                                    @foreach ($contacts['contacts'] as $index => $contact)
                                        @php
                                            $key = match (true) {
                                                str_contains((string) __($ns.'.contacts.faq'), (string) $contact['label']) => 'faq',
                                                str_contains((string) __($ns.'.contacts.phone'), (string) $contact['label']) => 'phone',
                                                str_contains((string) __($ns.'.contacts.appointment'), (string) $contact['label']) => 'appointment',
                                                default => 'assistance',
                                            };
                                            $resolved = $contactLinks[$key];
                                        @endphp
                                        <li>
                                            <a class="list-item left-icon" href="{{ $resolved['url'] }}"{{ $key === 'phone' ? '' : ' data-element="contacts"' }}>
                                                <svg class="icon icon-primary icon-sm left" aria-hidden="true"><use href="{{ $sprite }}#{{ $resolved['icon'] }}"></use></svg>
                                                <span>{{ $contact['label'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <style>
        .leaflet-container { z-index: 1; }
        .segnalazioni-map map-lit,
        .segnalazioni-map map-lit .geo-map-leaflet { min-height: clamp(11.5rem, 52vw, 46rem); }
        #segnalazioni-elenco-root[x-cloak] { display: none; }
    </style>
