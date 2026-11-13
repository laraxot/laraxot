@props(['data' => []])

@php
    use Illuminate\Support\Str;

    $navigation = $data['navigation'] ?? [
        ['label' => 'Sito', 'url' => '#', 'active' => true],
        ['label' => 'Flussi di servizio', 'url' => '#servizi'],
    ];

    $tabs = $data['tabs'] ?? [];
    $sections = $data['sections'] ?? [];

    if ($sections === []) {
        foreach ($tabs as $tab) {
            $pages = $tab['pages'] ?? [];

            if ($pages === []) {
                continue;
            }

            $groupedPages = collect($pages)
                ->filter(static fn (mixed $page): bool => is_array($page) && isset($page['group'], $page['pages']));

            if ($groupedPages->isNotEmpty()) {
                foreach ($groupedPages as $group) {
                    $sections[] = [
                        'id' => Str::slug((string) $group['group']),
                        'label' => $group['group'],
                        'pages' => $group['pages'],
                    ];
                }

                continue;
            }

            $sections[] = [
                'id' => $tab['id'] ?? Str::slug((string) ($tab['label'] ?? 'sezione')),
                'label' => $tab['label'] ?? '',
                'pages' => $pages,
            ];
        }
    }
@endphp

<div class="mx-auto my-6 w-full max-w-[1320px] px-5 pt-2">
    <header class="pl-4">
        <h1 class="text-5xl font-bold leading-[1.16] text-[#191919]">
            {{ $data['title'] ?? 'I templates HTML del modello per i comuni' }}
        </h1>

        @if(! empty($data['version_label']))
            <a class="mt-1 inline-block text-base font-semibold text-[#5d7083] hover:underline focus:outline-none focus:ring-2 focus:ring-[#007a52]" href="{{ $data['version_url'] ?? '#' }}">
                {{ $data['version_label'] }}
            </a>
        @endif
    </header>

    <nav class="mt-6 border-b border-[#d9d9d9]" aria-label="{{ __('pub_theme::components_common.a11y.index_sections.label') }}">
        <ul class="flex list-none gap-0 p-0" role="list">
            @foreach($navigation as $item)
                <li class="w-1/2 text-center">
                    <a href="{{ $item['url'] ?? '#' }}"
                       @if($item['active'] ?? false) aria-current="page" @endif
                       class="block border-b-2 px-4 py-3 text-base font-semibold focus:outline-none focus:ring-2 focus:ring-[#007a52] {{ ($item['active'] ?? false) ? 'border-[#007a52] text-[#007a52]' : 'border-transparent text-[#5d7083] hover:border-[#007a52] hover:text-[#007a52]' }}">
                        {{ $item['label'] ?? '' }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="mt-8 grid grid-cols-1 gap-4 lg:grid-cols-[416px_minmax(0,1fr)]">
        <aside>
            <h2 class="mb-3 text-base font-semibold uppercase text-[#191919]">
                {{ $data['heading'] ?? 'INDICE' }}
            </h2>
            <nav aria-label="{{ __('pub_theme::components_common.a11y.page_index.label') }}">
                <ul class="m-0 list-none border-l-2 border-[#00a878] p-0">
                    @foreach($sections as $section)
                        @php
                            $sectionId = $section['id'] ?? Str::slug((string) ($section['label'] ?? 'sezione'));
                        @endphp
                        <li>
                            <a href="#{{ $sectionId }}" class="block px-4 py-1 text-base font-semibold text-[#007a52] hover:underline focus:outline-none focus:ring-2 focus:ring-[#007a52]">
                                {{ $section['label'] ?? '' }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        <div class="min-w-0">
            @foreach($sections as $section)
                @php
                    $sectionId = $section['id'] ?? Str::slug((string) ($section['label'] ?? 'sezione'));
                @endphp
                <section id="{{ $sectionId }}" class="scroll-mt-6 {{ $loop->first ? '' : 'mt-10' }}">
                    <h2 class="text-[2.5rem] font-bold leading-[1.2] text-[#5d7083]">
                        {{ $section['label'] ?? '' }}
                    </h2>
                    @if(! empty($section['description']))
                        <p class="mb-2 text-base text-[#191919]">{{ $section['description'] }}</p>
                    @endif

                    <ul class="m-0 list-none divide-y divide-[#d9d9d9] border-b border-[#d9d9d9] p-0">
                        @foreach($section['pages'] ?? [] as $page)
                            @php
                                $file = $page['file'] ?? '';

                                if ($file === '') {
                                    $path = parse_url((string) ($page['url'] ?? ''), PHP_URL_PATH);
                                    $file = basename((string) $path).'.html';
                                }
                            @endphp
                            <li class="py-3">
                                <a href="{{ $page['url'] ?? '#' }}" class="grid grid-cols-1 gap-1 text-[#007a52] focus:outline-none focus:ring-2 focus:ring-[#007a52] sm:grid-cols-[minmax(0,1fr)_180px] sm:gap-4">
                                    <span>
                                        <span class="block text-base font-semibold hover:underline">{{ $page['name'] ?? '' }}</span>
                                        @if(! empty($page['description']))
                                            <span class="block text-sm leading-5 text-[#5d7083]">{{ $page['description'] }}</span>
                                        @endif
                                    </span>
                                    <span class="text-left text-sm text-[#5d7083] sm:text-right">{{ $file }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    </div>
</div>
