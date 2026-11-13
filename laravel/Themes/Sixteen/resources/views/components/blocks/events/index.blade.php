{{--
  Callers: CMS events.index → pub_theme::components.blocks.events.index
  (Cms page.blade.php include via content_blocks view). Unique FO events listing.
  Reads: ThemeComposer Article(event) + CMS $data / config sixteen.events.parity. No writes.
  User: «devi sempre correggere… migliorare… senza conferma» + parity Design Comuni eventi.
--}}
@props(['data' => []])

@php
    $composer = app(\Modules\Blog\View\Composers\ThemeComposer::class);
    $cmsUrl = static fn (string $path): string => \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl($path);
    // Chrome DC (feedback/contacts): CMS JSON > config tema > default a11y (mai h2 vuoti).
    $parityFromConfig = config('sixteen.sixteen.events.parity')
        ?? config('sixteen.events.parity')
        ?? [];
    $blogChrome = config('sixteen.sixteen.blog.parity')
        ?? config('sixteen.blog.parity')
        ?? [];
    $defaultFeedback = [
        'title' => __('pub_theme::news.index.feedback.title'),
        'legend' => __('pub_theme::news.index.feedback.legend'),
        'star_label' => __('pub_theme::news.index.feedback.star_label'),
        'thanks' => __('pub_theme::news.index.feedback.thanks'),
        'positive' => [
            'question' => __('pub_theme::news.index.feedback.positive.question'),
            'answers' => [
                __('pub_theme::news.index.feedback.positive.answers.clear'),
                __('pub_theme::news.index.feedback.positive.answers.complete'),
                __('pub_theme::news.index.feedback.positive.answers.progress'),
                __('pub_theme::news.index.feedback.positive.answers.technical'),
                __('pub_theme::news.index.feedback.positive.answers.other'),
            ],
        ],
        'negative' => [
            'question' => __('pub_theme::news.index.feedback.negative.question'),
            'answers' => [
                __('pub_theme::news.index.feedback.negative.answers.clear'),
                __('pub_theme::news.index.feedback.negative.answers.complete'),
                __('pub_theme::news.index.feedback.negative.answers.progress'),
                __('pub_theme::news.index.feedback.negative.answers.technical'),
                __('pub_theme::news.index.feedback.negative.answers.other'),
            ],
        ],
        'detail' => [
            'question' => __('pub_theme::news.index.feedback.detail.question'),
            'label' => __('pub_theme::news.index.feedback.detail.label'),
            'help' => __('pub_theme::news.index.feedback.detail.help'),
        ],
        'back' => __('pub_theme::news.index.feedback.back'),
        'next' => __('pub_theme::news.index.feedback.next'),
    ];
    $isItalian = app()->getLocale() === 'it';
    $parity = array_replace_recursive(
        [
            'title' => __('pub_theme::navigation.homepage.events'),
            'hero' => [
                'text' => 'Tutti gli eventi, le iniziative e i luoghi d’interesse per scoprire e vivere il territorio comunale.',
                'image' => ['file' => 'roma-colosseo.png', 'title' => 'Colosseo, Roma', 'alt' => 'Colosseo di Roma'],
                'caption' => 'Colosseo, Roma. Foto di Griffin Wooldridge.',
            ],
            'card' => [
                'read_more' => __('pub_theme::news.read_more'),
                'read_more_aria' => __('pub_theme::news.read_more').': %s',
                'read_more_hidden' => '',
            ],
            'locations' => [
                ['image' => ['file' => 'luoghi-castello-1.png', 'title' => 'Il castello normanno', 'alt' => 'Il castello normanno'], 'title' => 'Il castello normanno', 'text' => 'Scopri la storia e i luoghi di interesse del territorio.'],
                ['image' => ['file' => 'luoghi-castello-2.png', 'title' => 'Il castello normanno', 'alt' => 'Il castello normanno'], 'title' => 'Il castello normanno', 'text' => 'Scopri il programma degli eventi dell’estate.'],
                ['image' => ['file' => 'luoghi-castello-3.png', 'title' => 'Il castello normanno', 'alt' => 'Il castello normanno'], 'title' => 'Il castello normanno', 'text' => 'Un luogo da vivere e conoscere.'],
                ['image' => ['file' => 'luoghi-gipsoteca.png', 'title' => 'La Gipsoteca canoviana', 'alt' => 'La Gipsoteca canoviana'], 'title' => 'La Gipsoteca canoviana', 'text' => 'Modelli in gesso e bozzetti dello scultore Antonio Canova.'],
                ['image' => ['file' => 'luoghi-tomba.png', 'title' => 'Tomba Brion', 'alt' => 'Tomba Brion'], 'title' => 'Tomba Brion', 'text' => 'Un luogo affascinante e ricco di simbologia.'],
                ['image' => ['file' => 'luoghi-castello-4.png', 'title' => 'Il castello normanno', 'alt' => 'Il castello normanno'], 'title' => 'Il castello normanno', 'text' => 'Un itinerario per scoprire il territorio.'],
            ],
            'feedback' => $defaultFeedback,
            'contacts' => is_array($blogChrome['contacts'] ?? null) ? $blogChrome['contacts'] : [],
        ],
        is_array($parityFromConfig) ? array_replace($parityFromConfig, [
            'feedback' => $isItalian && is_array($parityFromConfig['feedback'] ?? null) ? $parityFromConfig['feedback'] : [],
        ]) : [],
        $isItalian && is_array($blogChrome['feedback'] ?? null) ? ['feedback' => $blogChrome['feedback']] : [],
        array_filter([
            'feedback' => is_array($data['feedback'] ?? null) ? $data['feedback'] : null,
            'contacts' => is_array($data['contacts'] ?? null) ? $data['contacts'] : null,
        ]),
        is_array($data['parity'] ?? null) ? $data['parity'] : [],
        is_array($data) ? $data : [],
    );
    // I luoghi e il Colosseo del riferimento statico non sono contenuti FixCity.
    // Restano solo se il JSON della pagina li passa esplicitamente.
    if (! is_array($data['locations'] ?? null)) {
        $parity['locations'] = [];
    }
    if (! is_array(data_get($data, 'hero.image'))) {
        $parity['hero']['image'] = ['file' => '', 'url' => '', 'title' => '', 'alt' => ''];
        $parity['hero']['caption'] = '';
    }
    foreach (['main', 'problems'] as $group) {
                $parity['contacts'][$group]['items'] = array_map(
            static function (array $contact) use ($cmsUrl): array {
                $url = (string) ($contact['url'] ?? '#');
                $contact['url'] = $url === '#' || str_starts_with($url, '#') || str_starts_with($url, 'http') || str_starts_with($url, 'tel:')
                    ? $url
                    : $cmsUrl($url);

                return $contact;
            },
            $parity['contacts'][$group]['items'] ?? [],
        );
    }
    $featured = $composer->featuredEvents(6);
    // Le evidenza sono escluse nella query: pagine piene e pager coerente. Ordine: prossimi eventi, poi passati.
    $featuredIds = array_values(array_filter(
        $featured->map(static fn ($item): int|string|null => $item instanceof \Modules\Blog\Models\Article && (is_int($item->getKey()) || is_string($item->getKey())) ? $item->getKey() : null)->all()
    ));
    $events = $composer->paginatedEvents(9, $featuredIds);

    $toCard = static function ($event): ?array {
        if (! $event instanceof \Modules\Blog\Models\Article) {
            return null;
        }
        $start = filled($event->event_start_date) ? \Illuminate\Support\Carbon::parse($event->event_start_date) : null;
        $title = $event->getTitle();
        if ($title === '') {
            return null;
        }
        $url = route('container0.view', ['container0' => 'events', 'slug0' => (string) ($event->getAttribute('slug') ?? '')]);
        $category = $event->category?->getAttribute('name') ?: $event->category?->getAttribute('title');
        $date = $start?->translatedFormat('j M Y');

        return [
            'image' => ['url' => (string) ($event->getAttribute('main_image_url') ?? ''), 'title' => $title, 'alt' => $title],
            'category' => ['label' => is_string($category) && $category !== '' ? strtoupper($category) : mb_strtoupper(__('pub_theme::news.index.default_event_category')), 'url' => $url],
            'title' => $title,
            'url' => $url,
            'text' => (string) ($event->getAttribute('excerpt') ?: $event->getAttribute('description') ?: ''),
            'read_more_url' => $url,
            'calendar' => $start ? ['date' => $start->format('d'), 'day' => $start->translatedFormat('F')] : null,
            'date' => is_string($date) ? strtoupper($date) : null,
        ];
    };

    $featuredItems = $featured->map($toCard)->filter()->values()->all();
    $exploreItems = collect($events->items())->map($toCard)->filter()->values()->all();
    $locationItems = is_array($parity['locations'] ?? null) ? $parity['locations'] : [];

    $sections = [];
    if ($featuredItems !== []) {
        $sections[] = [
            'style' => 'grey',
            'title' => $data['featured_title'] ?? __('pub_theme::navigation.homepage.featured_events'),
            'items' => $featuredItems,
        ];
    }
    $sections[] = [
        'style' => 'plain',
        'title' => $data['explore_title'] ?? __('pub_theme::navigation.homepage.all_events'),
        'items' => $exploreItems,
        'empty' => $data['empty'] ?? __('pub_theme::navigation.homepage.empty_events'),
        'pagination' => $events,
        'pagination_label' => $data['pagination_label'] ?? 'Paginazione degli eventi',
    ];
    if ($locationItems !== []) {
        $sections[] = [
            'style' => 'plain',
            'title' => $data['locations_title'] ?? __('pub_theme::navigation.homepage.featured_locations'),
            'items' => $locationItems,
            'button' => ['label' => __('pub_theme::navigation.homepage.all_locations'), 'url' => '#', 'element' => 'live-button-locations'],
        ];
    }

    $heroImage = is_array($parity['hero']['image'] ?? null) ? $parity['hero']['image'] : ['file' => '', 'url' => '', 'title' => '', 'alt' => ''];
    $heroCaption = (string) ($parity['hero']['caption'] ?? '');
    if (($heroImage['file'] ?? '') === '' && ($heroImage['url'] ?? '') === '') {
        foreach (array_merge($featuredItems, $exploreItems) as $candidate) {
            $candidateUrl = (string) ($candidate['image']['url'] ?? '');
            if ($candidateUrl === '' || $candidateUrl === '#') {
                continue;
            }
            $heroImage = [
                'file' => '',
                'url' => $candidateUrl,
                'title' => (string) ($candidate['title'] ?? ''),
                'alt' => (string) ($candidate['title'] ?? ''),
            ];
            $heroCaption = (string) ($candidate['title'] ?? '');
            break;
        }
    }

    $pageData = array_replace_recursive($parity, [
        'title' => $data['title'] ?? ($parity['title'] ?? __('pub_theme::navigation.homepage.events')),
        'breadcrumb' => [
            'items' => [['label' => $data['home_label'] ?? __('pub_theme::navigation.home'), 'url' => route('home')]],
            'current' => $data['title'] ?? ($parity['title'] ?? __('pub_theme::navigation.homepage.events')),
        ],
        'sections' => $sections,
        'hero' => [
            'text' => $data['intro'] ?? ($parity['hero']['text'] ?? ''),
            'image' => $heroImage,
            'caption' => $heroCaption,
        ],
        'card' => [
            'read_more' => $data['read_more'] ?? ($parity['card']['read_more'] ?? __('pub_theme::news.read_more')),
            'read_more_aria' => $parity['card']['read_more_aria'] ?? (__('pub_theme::news.read_more').': %s'),
            'read_more_hidden' => $parity['card']['read_more_hidden'] ?? '',
        ],
    ]);
@endphp

@include('pub_theme::components.blocks.events-page.design-comuni', ['data' => $pageData])
