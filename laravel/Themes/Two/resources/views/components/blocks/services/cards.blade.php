{{--
/**
 * Services Cards Block - Theme Two
 * Professional service cards with colored top border, icon, title, description and link
 */
--}}

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($title))
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
                @if(isset($description))
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $description }}</p>
                @endif
            </div>
        @endif

        @if(isset($services) && is_array($services))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min(count($services), 3) }} gap-8">
                @foreach($services as $index => $service)
                    @php
                        $colors = ['emerald', 'blue', 'indigo'];
                        $color = $service['color'] ?? $colors[$index % count($colors)];
                    @endphp
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        {{-- Colored top border --}}
                        <div class="absolute top-0 left-0 right-0 h-1 bg-{{ $color }}-500"></div>

                        @if(isset($service['icon']))
                            <div class="w-14 h-14 rounded-xl bg-{{ $color }}-50 text-{{ $color }}-600 flex items-center justify-center mb-6 group-hover:bg-{{ $color }}-500 group-hover:text-white transition-all duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    @switch($service['icon'])
                                        @case('shield-check')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                        @break
                                        @case('bolt')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                        @break
                                        @case('wrench')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-4.655 4.655a2.25 2.25 0 01-3.182-3.182l4.655-4.655M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 4.655"/>
                                        @break
                                        @case('document-check')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9.375-9zM10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12"/>
                                        @break
                                        @case('chart-bar')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                        @break
                                        @default
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    @endswitch
                                </svg>
                            </div>
                        @endif

                        @if(isset($service['title']))
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $service['title'] }}</h3>
                        @endif

                        @if(isset($service['description']))
                            <p class="text-gray-600 mb-6 leading-relaxed">{{ $service['description'] }}</p>
                        @endif

                        @if(isset($service['url']))
                            <a href="{{ $service['url'] }}"
                               class="inline-flex items-center gap-1.5 text-{{ $color }}-600 font-semibold hover:text-{{ $color }}-700 group-hover:gap-2.5 transition-all duration-300 text-sm">
                                {{ $service['link_label'] ?? 'Scopri di più' }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
