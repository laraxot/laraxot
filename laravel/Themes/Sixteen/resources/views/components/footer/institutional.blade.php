{{-- Bootstrap Italia Footer Component for PA - Institutional Footer --}}
@props([
    'institutionName' => config('app.name', 'Comune di '),
    'institutionLogo' => null,
    'institutionAddress' => 'Piazza del Municipio, 1',
    'institutionPhone' => '+39 0123 456789',
    'institutionEmail' => 'protocollo@comune.example.it',
    'institutionPec' => 'comune.example@pec.it',
    'socialLinks' => [],
    'quickLinks' => [],
    'serviceLinks' => [],
    'legalLinks' => [],
    'showBackToTop' => true
])

<footer class="it-footer-wrapper bg-italia-gray-900 text-white">
    {{-- Main Footer Section --}}
    <div class="container-italia">
        <div class="py-12">
            {{-- Institution Info and Contact --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                {{-- Institution Branding --}}
                <div class="lg:col-span-2">
                    @if($institutionLogo)
                    <div class="mb-4">
                        <img src="{{ $institutionLogo }}" alt="{{ $institutionName }}" class="h-16 w-auto">
                    </div>
                    @endif
                    
                    <h3 class="text-xl font-bold mb-4">{{ $institutionName }}</h3>
                    
                    <div class="space-y-2 text-italia-gray-300">
                        @if($institutionAddress)
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ $institutionAddress }}</span>
                        </div>
                        @endif
                        
                        @if($institutionPhone)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                            </svg>
                            <a href="tel:{{ $institutionPhone }}" class="hover:text-primary-300 transition-colors">{{ $institutionPhone }}</a>
                        </div>
                        @endif
                        
                        @if($institutionEmail)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <a href="mailto:{{ $institutionEmail }}" class="hover:text-primary-300 transition-colors">{{ $institutionEmail }}</a>
                        </div>
                        @endif
                        
                        @if($institutionPec)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.2 6.5 10.266a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                            </svg>
                            <a href="mailto:{{ $institutionPec }}" class="hover:text-primary-300 transition-colors">{{ $institutionPec }}</a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Quick Links --}}
                @if(!empty($quickLinks))
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b border-italia-gray-700 pb-2">Link rapidi</h4>
                    <ul class="space-y-2">
                        @foreach($quickLinks as $link)
                        <li>
                            <a href="{{ $link['url'] ?? '#' }}" 
                               class="text-italia-gray-300 hover:text-primary-300 transition-colors"
                               @if($link['target'] ?? false) target="{{ $link['target'] }}" @endif>
                                {{ $link['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Services --}}
                @if(!empty($serviceLinks))
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b border-italia-gray-700 pb-2">Servizi</h4>
                    <ul class="space-y-2">
                        @foreach($serviceLinks as $link)
                        <li>
                            <a href="{{ $link['url'] ?? '#' }}" 
                               class="text-italia-gray-300 hover:text-primary-300 transition-colors"
                               @if($link['target'] ?? false) target="{{ $link['target'] }}" @endif>
                                {{ $link['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            {{-- Social Media and Additional Links --}}
            <div class="border-t border-italia-gray-700 pt-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    {{-- Social Links --}}
                    @if(!empty($socialLinks))
                    <div class="flex space-x-4 mb-4 md:mb-0">
                        @foreach($socialLinks as $social)
                        <a href="{{ $social['url'] ?? '#' }}" 
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-italia-gray-400 hover:text-primary-300 transition-colors"
                           aria-label="{{ $social['name'] ?? 'Social' }}">
                            @if(isset($social['icon']))
                                {!! $social['icon'] !!}
                            @else
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            @endif
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Legal Links --}}
                    @if(!empty($legalLinks))
                    <div class="flex flex-wrap gap-6 text-sm text-italia-gray-400">
                        @foreach($legalLinks as $link)
                        <a href="{{ $link['url'] ?? '#' }}" 
                           class="hover:text-primary-300 transition-colors"
                           @if($link['target'] ?? false) target="{{ $link['target'] }}" @endif>
                            {{ $link['label'] }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright and Bottom Bar --}}
    <div class="bg-italia-gray-950 py-4">
        <div class="container-italia">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="text-italia-gray-400 text-sm mb-2 md:mb-0">
                    &copy; {{ date('Y') }} {{ $institutionName }} - Tutti i diritti riservati
                </div>
                
                <div class="text-italia-gray-400 text-sm">
                    P.IVA 01234567890 - C.F. 90123456789
                </div>
            </div>
        </div>
    </div>

    {{-- Back to Top Button --}}
    @if($showBackToTop)
    <button type="button"
            class="fixed bottom-8 right-8 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transition-all opacity-0 transform translate-y-4"
            x-data="{
                show: false,
                init() {
                    window.addEventListener('scroll', () => {
                        this.show = window.scrollY > 300;
                    });
                },
                scrollToTop() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }"
            @click="scrollToTop"
            :class="{
                'opacity-100 translate-y-0': show,
                'opacity-0 translate-y-4': !show
            }">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
        <span class="sr-only">Torna all'inizio</span>
    </button>
    @endif
</footer>