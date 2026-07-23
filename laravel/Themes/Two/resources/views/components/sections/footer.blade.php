@php
    $footerBlock = Arr::first($blocks, fn($item) => $item->slug == 'footer');
    $data = $footerBlock->data ?? [];
    $brand = $data['brand'] ?? [];
    $social = $data['social'] ?? [];
    $normative = $data['normative'] ?? [];
    $services = $data['services'] ?? [];
    $contact = $data['contact'] ?? [];
    $legal = $data['legal'] ?? [];
@endphp

<footer class="bg-gradient-to-br from-[#0a2342] via-[#0F3460] to-[#0d2a4a] text-white relative overflow-hidden" role="contentinfo">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Branding & About --}}
            <div>
                <span class="text-2xl font-bold block mb-2">{{ $brand['name'] ?? 'Marco Sottana' }}</span>
                <span class="text-sm font-semibold uppercase tracking-widest text-gray-300 mb-2">{{ $brand['subtitle'] ?? 'Consulenza Sicurezza' }}</span>
                <p class="text-gray-200 mb-4 text-sm leading-relaxed">
                    {{ $brand['description'] ?? 'Esperto di radioprotezione e sicurezza radiologica per studi dentistici e veterinari.' }}
                </p>
                <div class="flex space-x-4">
                    @if($social['linkedin'] ?? null)
                        <a href="{{ $social['linkedin'] ?? '#' }}" class="p-2 bg-white/10 rounded-lg hover:bg-brand-green transition-colors" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn - Profilo LinkedIn di Marco Sottana">
                            <x-filament::icon icon="techplanner-linkedin" class="w-5 h-5 text-current" aria-hidden="true" />
                        </a>
                    @endif
                    @if($social['facebook'] ?? null)
                        <a href="{{ $social['facebook'] ?? '#' }}" class="p-2 bg-white/10 rounded-lg hover:bg-brand-green transition-colors" target="_blank" rel="noopener noreferrer" aria-label="Facebook - Pagina Facebook di Marco Sottana">
                            <x-filament::icon icon="techplanner-facebook" class="w-5 h-5 text-current" aria-hidden="true" />
                        </a>
                    @endif
                    @if($social['instagram'] ?? null)
                        <a href="{{ $social['instagram'] ?? '#' }}" class="p-2 bg-white/10 rounded-lg hover:bg-brand-green transition-colors" target="_blank" rel="noopener noreferrer" aria-label="Instagram - Profilo Instagram di Marco Sottana">
                            <x-heroicon-o-camera class="w-5 h-5 text-current" aria-hidden="true" />
                        </a>
                    @endif
                </div>
            </div>

            {{-- Normative --}}
            <div>
                <span class="text-lg font-semibold mb-4 block text-orange-300 flex items-center">
                    <x-heroicon-o-shield-check class="w-5 h-5 mr-2" aria-hidden="true" />
                    {{ $normative['title'] ?? 'Normative & Certificazioni' }}
                </span>
                <ul class="space-y-3 text-sm text-gray-300">
                    @foreach($normative['items'] ?? [] as $item)
                        <li class="border-b border-white/10 pb-2 last:border-0">
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Services Links --}}
            <div>
                <span class="text-lg font-semibold mb-4 block">{{ $services['title'] ?? 'Servizi' }}</span>
                <ul class="space-y-2 text-gray-300 text-sm">
                    @foreach($services['items'] ?? [] as $item)
                        <li><a class="hover:text-brand-green transition-colors" href="/it/servizi">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact Info --}}
            <div>
                <span class="text-lg font-semibold mb-4 block">{{ $contact['title'] ?? 'Contatti' }}</span>
                <ul class="space-y-3 text-sm">
                    @if($contact['address'] ?? null)
                        <li class="flex items-start space-x-3">
                            <x-heroicon-o-map-pin class="w-5 h-5 text-brand-green flex-shrink-0 mt-1" aria-hidden="true" />
                            <span class="text-gray-300">{{ $contact['address'] }}<br>{{ $contact['city'] ?? '' }}</span>
                        </li>
                    @endif
                    @if($contact['email'] ?? null)
                        <li class="flex items-start space-x-3">
                            <x-heroicon-o-envelope class="w-5 h-5 text-[#2D8659] flex-shrink-0 mt-1" aria-hidden="true" />
                            <a href="mailto:{{ $contact['email'] }}" class="text-gray-300 hover:text-[#2D8659] transition-colors">{{ $contact['email'] }}</a>
                        </li>
                    @endif
                    @if($contact['phone'] ?? null)
                        <li class="flex items-start space-x-3">
                            <x-heroicon-o-phone class="w-5 h-5 text-[#2D8659] flex-shrink-0 mt-1" aria-hidden="true" />
                            <a href="tel:{{ $contact['phone'] }}" class="text-gray-300 hover:text-[#2D8659] transition-colors">{{ $contact['phone'] }}</a>
                        </li>
                    @endif
                </ul>
                @php
                    $hasPiva = isset($contact['piva']) && $contact['piva'];
                    $hasRea = isset($contact['rea']) && $contact['rea'];
                @endphp
                @if($hasPiva || $hasRea)
                    <div class="mt-4 pt-4 border-t border-white/10">
                        @if($hasPiva)
                            <p class="text-xs text-gray-400">P.IVA: {{ $contact['piva'] }}</p>
                        @endif
                        @if($hasRea)
                            <p class="text-xs text-gray-400">REA: {{ $contact['rea'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="mt-12 pt-8 border-t border-white/10">
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold mb-4">Contattaci Subito</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    @if($contact['phone'] ?? null)
                    <a href="tel:{{ $contact['phone'] }}" class="flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all duration-300 hover:scale-105 shadow-lg" aria-label="Chiama {{ $contact['phone'] }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>Chiama Ora</span>
                    </a>
                    @endif
                    @if($contact['mobile'] ?? null)
                    <a href="https://wa.me/39{{ str_replace([' ', '-'], '', $contact['mobile']) }}" target="_blank" class="flex items-center gap-2 px-5 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-semibold transition-all duration-300 hover:scale-105 shadow-lg" aria-label="Scrivi su WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.347-.446.52-.149.174-.198.298-.497.099-.198.248-.05.371-.025-.52.075-.149-.074-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118 571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335 .157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span>WhatsApp</span>
                    </a>
                    @endif
                    <a href="/it/contacts" class="flex items-center gap-2 px-5 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-semibold transition-all duration-300 hover:scale-105 shadow-lg" aria-label="Prenota un appuntamento">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Prenota Appuntamento</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Testimonials --}}
        <div class="mt-12 pt-8 border-t border-white/10 bg-[#0b2540]">
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-white">Dicono di Noi</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                <div class="bg-white/5 backdrop-blur-md p-6 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <svg class="w-8 h-8 text-orange-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-300 mb-4 italic leading-relaxed">Servizio eccellente e professionale. Marco è molto competente e disponibile per qualsiasi chiarimento.</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">D</div>
                        <div>
                            <p class="text-white font-semibold text-sm">Dr. Alberto Rossi</p>
                            <p class="text-gray-400 text-xs">Studio Dentistico Rossi</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white/5 backdrop-blur-md p-6 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <svg class="w-8 h-8 text-orange-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-300 mb-4 italic leading-relaxed">Puntuali, precisi e sempre aggiornati sulle normative. Consiglio vivamente.</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">D</div>
                        <div>
                            <p class="text-white font-semibold text-sm">Dr.ssa Giulia Bianchi</p>
                            <p class="text-gray-400 text-xs">VetLife Ambulatorio</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Certifications --}}
        <div class="mt-12 pt-8 border-t border-white/10 bg-[#0c2744]">
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-white">Certificazioni</h3>
            </div>
            <div class="flex flex-wrap justify-center items-center gap-6">
                <div class="flex flex-col items-center gap-1 bg-white/5 px-4 py-3 rounded-lg border border-white/10">
                    <div class="flex items-center gap-2 text-orange-400">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-sm text-white">Esperto di Radioprotezione</span>
                    </div>
                    <p class="text-xs text-gray-400">Ministero della Salute</p>
                    <p class="text-xs text-gray-500">Valido fino a: 2026-12-31</p>
                </div>
                <div class="flex flex-col items-center gap-1 bg-white/5 px-4 py-3 rounded-lg border border-white/10">
                    <div class="flex items-center gap-2 text-orange-400">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-sm text-white">Esperto di Radioprotezione</span>
                    </div>
                    <p class="text-xs text-gray-400">Ministero della Salute</p>
                    <p class="text-xs text-gray-500">Valido fino a: 2026-12-31</p>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="mt-12 pt-8 border-t border-white/10">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-gray-400 text-sm">{{ $legal['copyright'] ?? '© ' . date('Y') . ' Marco Sottana – Consulenza Sicurezza. Tutti i diritti riservati.' }}</p>
                <div class="flex space-x-6 text-sm">
                    @foreach($legal['links'] ?? [] as $link)
                        <a class="text-gray-400 hover:text-[#2D8659] transition-colors" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>