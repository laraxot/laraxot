{{-- Topics Featured - Design Comuni Style (Tailwind CSS) --}}
@props([
    'title'               => 'Argomenti in evidenza',
    'background_image'    => null,
    'items'               => [],   // [ ['title','description','links'=>[],'cta_label','cta_url'] ]
    'other_topics'        => [],   // [ ['title','url'] ]
    'all_topics_label'    => 'Mostra tutti',
    'all_topics_url'      => '#',
    'themed_sites_title'  => 'Siti tematici',
    'themed_sites'        => [],   // [ ['title','description','url','image','color'] ]
])

{{-- Sezione principale con sfondo immagine --}}
{{-- FIXED: Changed from bg-blue-900 to green gradient to match Design Comuni reference --}}
<section class="relative py-12 lg:py-20 bg-gradient-to-br from-emerald-600 to-emerald-700 text-white overflow-hidden">

    {{-- Background image overlay --}}
    @if($background_image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $background_image }}" alt="" class="w-full h-full object-cover opacity-30" aria-hidden="true" />
        </div>
    @endif

    <div class="relative z-10 container mx-auto px-4">

        {{-- Titolo sezione --}}
        @if($title)
            <h2 class="text-2xl lg:text-3xl font-semibold text-white mb-10">{{ $title }}</h2>
        @endif

        {{-- 3 card topic --}}
        @if(!empty($items))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                @foreach($items as $item)
                    <div class="bg-white/10 backdrop-blur-sm rounded border border-white/20 p-6 flex flex-col">
                        {{-- Titolo topic --}}
                        @if(!empty($item['title']))
                            <h3 class="text-lg font-semibold text-white mb-2">{{ $item['title'] }}</h3>
                        @endif

                        {{-- Descrizione --}}
                        @if(!empty($item['description']))
                            <p class="text-blue-100 text-sm mb-4 leading-relaxed">{{ $item['description'] }}</p>
                        @endif

                        {{-- Lista link opzionale --}}
                        @if(!empty($item['links']))
                            <ul class="mb-4 space-y-1">
                                @foreach($item['links'] as $link)
                                    <li>
                                        <a href="{{ $link['url'] ?? '#' }}"
                                           class="flex items-center gap-1 text-blue-200 hover:text-white text-sm focus:outline-none focus:underline">
                                            <x-filament::icon icon="heroicon-o-arrow-right" class="w-3 h-3 flex-shrink-0" />
                                            {{ $link['label'] ?? $link['title'] ?? '' }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- CTA "Esplora argomento" --}}
                        @if(!empty($item['cta_url']))
                            <div class="mt-auto pt-4">
                                <a href="{{ $item['cta_url'] }}"
                                   class="inline-flex items-center gap-1 text-white border border-white rounded px-4 py-2 text-sm font-semibold hover:bg-white hover:text-blue-900 transition-colors focus:outline-none">
                                    {{ $item['cta_label'] ?? 'Esplora argomento' }}
                                    <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4" />
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Altri argomenti: label + tag orizzontali --}}
        @if(!empty($other_topics))
            <div class="mb-8">
                <p class="text-blue-200 text-sm font-semibold mb-3 uppercase tracking-wider">Altri argomenti</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($other_topics as $topic)
                        <a href="{{ $topic['url'] ?? '#' }}"
                           class="inline-block bg-white/10 hover:bg-white/20 text-white text-sm px-3 py-1 rounded-full border border-white/30 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50">
                            {{ $topic['title'] ?? '' }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Bottone "Mostra tutti" --}}
        @if($all_topics_url && $all_topics_label)
            <div class="mb-2">
                <a href="{{ $all_topics_url }}"
                   class="inline-flex items-center gap-1 text-white font-semibold underline underline-offset-4 hover:no-underline text-sm focus:outline-none">
                    {{ $all_topics_label }}
                    <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4" />
                </a>
            </div>
        @endif

    </div>
</section>

{{-- Siti tematici --}}
@if(!empty($themed_sites))
    <section class="bg-white py-10 lg:py-14">
        <div class="container mx-auto px-4">

            @if($themed_sites_title)
                <h2 class="text-xl lg:text-2xl font-semibold text-gray-900 mb-6">{{ $themed_sites_title }}</h2>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($themed_sites as $site)
                    @php
                        $colorMap = [
                            'blue'      => 'bg-blue-700 text-white',
                            'yellow'    => 'bg-yellow-400 text-gray-900',
                            'dark'      => 'bg-gray-800 text-white',
                            'gray'      => 'bg-gray-700 text-white',
                        ];
                        $color = $site['color'] ?? 'blue';
                        $colorClass = $colorMap[$color] ?? 'bg-blue-700 text-white';
                    @endphp

                    <a href="{{ $site['url'] ?? '#' }}"
                       class="group block rounded overflow-hidden shadow-sm hover:shadow-md transition-shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                        <div class="{{ $colorClass }} p-6 flex items-start gap-4 h-full">
                            {{-- Avatar immagine --}}
                            @if(!empty($site['image']))
                                <img src="{{ $site['image'] }}"
                                     alt="{{ $site['title'] ?? '' }}"
                                     class="w-14 h-14 rounded-full object-cover flex-shrink-0 border-2 border-white/30" />
                            @endif
                            <div>
                                @if(!empty($site['title']))
                                    <h3 class="font-semibold text-base mb-1 group-hover:underline">{{ $site['title'] }}</h3>
                                @endif
                                @if(!empty($site['description']))
                                    <p class="text-sm opacity-80 leading-snug">{{ $site['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </section>
@endif
