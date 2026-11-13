{{--
    Homepage «Eventi» (Design Comuni sito/homepage.html, section#calendario: row-title + it-calendar-wrapper).
    Caller: config/local/fixcity/database/content/pages/home.json → content_blocks.{it,en,de,es}[*].data.view.
    Dati: ThemeComposer::paginatedArticlesComingSoon (Blog Article type=event, inizio futuro, piu' vicini prima),
    raggruppati per mese (h3) e giorno (h4) come nel riferimento. Nessun evento in arrivo → sezione non renderizzata.
    Senza JS Splide: elenco statico a griglia, stessa struttura di classi.
--}}
@props(['data' => []])

@php
    $composer = app(\Modules\Blog\View\Composers\ThemeComposer::class);
    $limit = max(1, (int) ($data['limit'] ?? 6));
    $eventsIndexUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::eventsIndex();

    /** @var array<string, array{label: string, days: array<string, array{day: string, weekday: string, full: string, events: list<array{title: string, url: string, image: string}>}>}> $months */
    $months = [];
    foreach ($composer->paginatedArticlesComingSoon($limit)->items() as $event) {
        if (! $event instanceof \Modules\Blog\Models\Article || $event->getTitle() === '' || blank($event->event_start_date)) {
            continue;
        }
        $start = \Illuminate\Support\Carbon::parse($event->event_start_date);
        $monthKey = $start->format('Y-m');
        $dayKey = $start->format('Y-m-d');
        $months[$monthKey]['label'] ??= \Illuminate\Support\Str::ucfirst($start->translatedFormat('F Y'));
        $months[$monthKey]['days'][$dayKey]['day'] ??= $start->format('j');
        $months[$monthKey]['days'][$dayKey]['weekday'] ??= $start->translatedFormat('D');
        $months[$monthKey]['days'][$dayKey]['full'] ??= $start->translatedFormat('l j F Y');
        $months[$monthKey]['days'][$dayKey]['events'][] = [
            'title' => $event->getTitle(),
            'url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::view('events', (string) $event->getAttribute('slug')),
            'image' => (string) $event->getAttribute('main_image_url'),
        ];
    }

    $heading = (string) ($data['heading'] ?? __('pub_theme::navigation.homepage.events'));
    $viewAllLabel = (string) ($data['view_all_label'] ?? '');
    $viewAllAria = (string) ($data['view_all_aria'] ?? $viewAllLabel);
@endphp

@if ($months !== [])
    <section id="calendario" class="fc-home-events" aria-labelledby="fc-home-events-title" data-section="home-events">
        <div class="section section-muted pb-5 px-lg-5 pt-0">
            <div class="container">
                <div class="row row-title pt-5 pb-3">
                    <div class="col-12 d-lg-flex justify-content-between">
                        <h2 id="fc-home-events-title">{{ $heading }}</h2>
                        @if ($viewAllLabel !== '')
                            <a class="read-more" href="{{ $eventsIndexUrl }}" aria-label="{{ $viewAllAria }}" data-element="events-view-all">
                                <span class="text">{{ $viewAllLabel }}</span>
                                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
                @foreach ($months as $month)
                    <div class="row row-calendar">
                        <div class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols it-calendar-wrapper">
                            <div class="it-header-block">
                                <div class="it-header-block-title">
                                    <h3 class="mb-0 text-center home-carousel-title">{{ $month['label'] }}</h3>
                                </div>
                            </div>
                            <ul class="it-carousel-all fc-home-events__days">
                                @foreach ($month['days'] as $day)
                                    <li>
                                        <div class="it-single-slide-wrapper h-100">
                                            <div class="card-wrapper h-100">
                                                <div class="card card-bg">
                                                    <div class="card-body">
                                                        <h4 class="card-title pb-4 mb-10 text-secondary">
                                                            <span aria-hidden="true">{{ $day['day'] }}<span>{{ $day['weekday'] }}</span></span>
                                                            <span class="visually-hidden">{{ $day['full'] }}</span>
                                                        </h4>
                                                        @foreach ($day['events'] as $item)
                                                            <p @class(['card-text px-2', 'pb-10 mb-10' => ! $loop->last, 'd-flex' => $item['image'] !== ''])>
                                                                @if ($item['image'] !== '')
                                                                    <img src="{{ $item['image'] }}" alt="" class="me-3 rounded" width="64" height="64" loading="lazy">
                                                                @endif
                                                                <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <style>
        .fc-home-events .section-muted { background: #f5f5f0; }
        .fc-home-events .row-title > div { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; align-items: baseline; justify-content: space-between; }
        .fc-home-events .read-more { display: inline-flex; align-items: center; gap: .35rem; font-weight: 600; color: #007a52; text-decoration: none; }
        .fc-home-events .row-calendar { margin: 0 0 1.5rem; }
        .fc-home-events .it-header-block { background: #007a52; border-radius: 4px 4px 0 0; padding: 1rem; }
        .fc-home-events__days { list-style: none; margin: 0; padding: 1rem 0 0; display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 14rem), 1fr)); gap: 1rem; }
        .fc-home-events .card { background: #fff; height: 100%; box-shadow: 0 2px 8px rgba(0, 0, 0, .08); }
        .fc-home-events .card-body { padding: 1.25rem; }
        .fc-home-events h4.card-title { font-size: 2rem; font-weight: 700; color: #5c6f82; border-bottom: 1px solid #e6e9f2; }
        .fc-home-events h4.card-title span span { display: block; font-size: .875rem; font-weight: 600; text-transform: uppercase; }
        .fc-home-events .card-text { gap: .75rem; align-items: center; }
        .fc-home-events .card-text img { width: 4rem; height: 4rem; object-fit: cover; flex: none; }
    </style>
@endif
