{{--
/**
 * Resources/Downloads Block - Theme Two
 * Blue gradient background with download cards and CTA buttons
 */
--}}

<section class="py-20 bg-gradient-to-br from-[#1e3a5f] via-[#1a4a6f] to-[#0d7377] text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($title))
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">{{ $title }}</h2>
                @if(isset($description))
                    <p class="text-lg text-white/70 max-w-2xl mx-auto">{{ $description }}</p>
                @endif
            </div>
        @endif

        @if(isset($resources) && is_array($resources))
            <div class="grid grid-cols-1 md:grid-cols-{{ min(count($resources), 2) }} gap-8">
                @foreach($resources as $resource)
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/10 hover:bg-white/15 transition-all duration-300 text-center">
                        {{-- Icon --}}
                        <div class="w-16 h-16 mx-auto mb-6 bg-white/10 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                        </div>

                        @if(isset($resource['title']))
                            <h3 class="text-xl font-bold text-white mb-3">{{ $resource['title'] }}</h3>
                        @endif

                        @if(isset($resource['description']))
                            <p class="text-white/60 mb-6 text-sm leading-relaxed">{{ $resource['description'] }}</p>
                        @endif

                        @if(isset($resource['url']))
                            <a href="{{ $resource['url'] }}"
                               class="inline-flex items-center justify-center gap-2 w-full px-6 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-all duration-300 shadow-lg hover:shadow-orange-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                {{ $resource['button_label'] ?? 'Scarica Guida PDF' }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
