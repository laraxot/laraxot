{{-- Bootstrap Italia Footer Component --}}
@props([
    'brandName' => config('app.name', 'Comune di '),
    'brandLogo' => null,
    'description' => null,
    'address' => [],
    'contacts' => [],
    'socialLinks' => [],
    'links' => [],
    'bottomLinks' => [],
    'showNewsletter' => false,
    'copyright' => null
])

<footer class="it-footer bg-primary-900 text-white">
    {{-- Main Footer Content --}}
    <div class="py-12">
        <div class="container-italia">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Brand Column --}}
                <div class="lg:col-span-2">
                    <div class="flex items-start mb-6">
                        @if($brandLogo)
                        <img src="{{ $brandLogo }}" alt="{{ $brandName }}" class="h-12 w-auto mr-4 flex-shrink-0">
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">{{ $brandName }}</h3>
                            @if($description)
                            <p class="text-primary-200 text-sm leading-relaxed">{{ $description }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Address --}}
                    @if(!empty($address))
                    <div class="mb-6">
                        <h4 class="font-semibold text-white mb-3">Indirizzo</h4>
                        <div class="text-primary-200 text-sm space-y-1">
                            @foreach($address as $line)
                            <div>{{ $line }}</div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Contacts --}}
                    @if(!empty($contacts))
                    <div class="mb-6">
                        <h4 class="font-semibold text-white mb-3">Contatti</h4>
                        <div class="space-y-2">
                            @foreach($contacts as $contact)
                            <div class="flex items-center text-sm text-primary-200">
                                @if($contact['icon'] ?? false)
                                <span class="mr-2">{!! $contact['icon'] !!}</span>
                                @endif
                                <span class="font-medium mr-2">{{ $contact['label'] ?? '' }}</span>
                                @if($contact['link'] ?? false)
                                <a href="{{ $contact['link'] }}" class="text-white hover:text-primary-100 transition-colors">
                                    {{ $contact['value'] }}
                                </a>
                                @else
                                <span>{{ $contact['value'] ?? '' }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Links Columns --}}
                @if(!empty($links))
                @foreach($links as $section)
                <div>
                    <h4 class="font-semibold text-white mb-4">{{ $section['title'] ?? '' }}</h4>
                    <ul class="space-y-2">
                        @foreach($section['items'] ?? [] as $link)
                        <li>
                            <a href="{{ $link['url'] ?? '#' }}" 
                               class="text-primary-200 hover:text-white transition-colors text-sm"
                               @if($link['external'] ?? false) target="_blank" rel="noopener noreferrer" @endif>
                                {{ $link['label'] }}
                                @if($link['external'] ?? false)
                                <svg class="inline w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                @endif
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
                @endif

                {{-- Newsletter --}}
                @if($showNewsletter)
                <div>
                    <h4 class="font-semibold text-white mb-4">Newsletter</h4>
                    <p class="text-primary-200 text-sm mb-4">Rimani aggiornato sulle ultime novità</p>
                    <form class="space-y-3">
                        <input type="email" 
                               placeholder="La tua email"
                               class="w-full px-3 py-2 text-italia-gray-900 bg-white rounded-md border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent text-sm">
                        <button type="submit" 
                                class="btn-italia bg-primary-700 hover:bg-primary-600 text-white w-full text-sm">
                            Iscriviti
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- Social Links --}}
            @if(!empty($socialLinks))
            <div class="mt-8 pt-8 border-t border-primary-800">
                <h4 class="font-semibold text-white mb-4">Seguici sui social</h4>
                <div class="flex space-x-4">
                    @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] ?? '#' }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="flex items-center justify-center w-10 h-10 bg-primary-800 hover:bg-primary-700 rounded-lg transition-colors">
                        <span class="sr-only">{{ $social['name'] ?? 'Social' }}</span>
                        @if($social['icon'] ?? false)
                            {!! $social['icon'] !!}
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Bottom Footer --}}
    <div class="bg-primary-950 py-4">
        <div class="container-italia">
            <div class="flex flex-col md:flex-row items-center justify-between text-sm text-primary-300">
                <div class="mb-2 md:mb-0">
                    {{ $copyright ?? '© ' . date('Y') . ' ' . $brandName . '. Tutti i diritti riservati.' }}
                </div>
                
                @if(!empty($bottomLinks))
                <div class="flex flex-wrap items-center space-x-4">
                    @foreach($bottomLinks as $link)
                    <a href="{{ $link['url'] ?? '#' }}" 
                       class="text-primary-300 hover:text-white transition-colors">
                        {{ $link['label'] }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</footer>