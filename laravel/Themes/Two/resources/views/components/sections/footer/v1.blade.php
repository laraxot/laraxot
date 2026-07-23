@php
    /**
     * Footer Component v1
     * 
     * Receives $blocks as DataCollection<BlockData> from Section component.
     * Each BlockData has: type, slug, data (array), view
     */
    
    // Extract footer data from DataCollection
    $footerBlock = null;
    
    // Handle both DataCollection and array
    if ($blocks instanceof \Spatie\LaravelData\DataCollection) {
        // $blocks is a DataCollection<BlockData>
        foreach ($blocks as $block) {
            // BlockData properties: type, slug, data, view
            if ($block->type === 'footer' && $block->slug === 'main-footer') {
                $footerBlock = $block->data;
                break;
            }
        }
    } elseif (is_array($blocks)) {
        // $blocks is a plain array
        foreach ($blocks as $block) {
            $type = is_array($block) ? ($block['type'] ?? '') : ($block->type ?? '');
            $slug = is_array($block) ? ($block['slug'] ?? '') : ($block->slug ?? '');
            if ($type === 'footer' && $slug === 'main-footer') {
                $footerBlock = is_array($block) ? ($block['data'] ?? []) : ($block->data ?? []);
                break;
            }
        }
    }
    
    // Fallback: if no block found, use empty array
    if (!is_array($footerBlock)) {
        $footerBlock = [];
    }
    
    // Extract sections with defaults
    $brand = $footerBlock['brand'] ?? [
        'name' => 'Sottana Service',
        'subtitle' => 'Consulenza Sicurezza e Radioprotezione',
        'description' => 'Specialisti in radioprotezione e sicurezza per studi dentistici e cliniche veterinarie. Partner di fiducia per la conformità normativa.'
    ];
    
    $social = $footerBlock['social'] ?? [];
    
    $normative = $footerBlock['normative'] ?? [
        'title' => 'Normative & Certificazioni',
        'items' => [
            [
                'label' => 'D.Lgs 101/2020',
                'description' => 'Attuazione della direttiva 2013/59/Euratom per la sicurezza radiologica.'
            ],
            [
                'label' => 'Esperti di Radioprotezione',
                'description' => 'Professionisti iscritti negli elenchi nominativi autorizzati.'
            ],
            [
                'label' => 'IEC 62353',
                'description' => 'Verifiche periodiche di sicurezza elettrica per apparecchi elettromedicali.'
            ]
        ]
    ];
    
    $services = $footerBlock['services'] ?? [
        'title' => 'Servizi',
        'items' => [
            'Controllo Radioprotezione',
            'Verifiche Elettromedicali',
            'Documenti di Valutazione del Rischio'
        ]
    ];
    
    // Extract contact data from items array structure
    $contactRaw = $footerBlock['contact'] ?? [];
    $contactItems = $contactRaw['items'] ?? [];
    
    // Helper to find value by type
    $findContactValue = function($type) use ($contactItems) {
        foreach ($contactItems as $item) {
            if (($item['type'] ?? '') === $type) return $item['value'] ?? null;
        }
        return null;
    };

    // Use values from block or defaults
    $address = $findContactValue('address') ?? 'Via Vanzo 86, 31021 Mogliano Veneto TV';
    $phone = $findContactValue('phone') ?? '+39 041 455552';
    $mobile = $findContactValue('mobile') ?? '+39 347 58 96 127';
    $email = $findContactValue('email') ?? 'studio@sottana.com';
    
    // Prepare items for the view loop if they don't exist in block data
    if (empty($contactItems)) {
        $contactItems = [
            ['type' => 'address', 'value' => $address],
            ['type' => 'email', 'value' => $email],
            ['type' => 'phone', 'value' => $phone, 'label' => 'Fisso'],
            ['type' => 'mobile', 'value' => $mobile, 'label' => 'Mobile'],
        ];
    }
    
    $contact = [
        'title' => $contactRaw['title'] ?? 'Contatti',
        'items' => $contactItems,
        'piva' => $contactRaw['piva'] ?? '05532540266',
        'rea' => $contactRaw['rea'] ?? 'TV - 451911'
    ];
    
    $legal = $footerBlock['legal'] ?? [
        'copyright' => '© 2026 Sottana Service - Consulenza Sicurezza e Radioprotezione. Tutti i diritti riservati.',
        'links' => [
             ['label' => 'Privacy Policy', 'url' => '/it/privacy'],
             ['label' => 'Termini e Condizioni', 'url' => '/it/terms']
        ]
    ];

    // Extract quick actions for the view
    $quickActions = $footerBlock['quick_actions'] ?? [
        'call' => ['phone' => $phone, 'label' => 'Chiama Ora'],
        'whatsapp' => ['number' => $mobile, 'label' => 'WhatsApp'],
        'appointment' => ['url' => '/it/contatti', 'label' => 'Prenota']
    ];
@endphp

<footer role="contentinfo" class="text-white relative overflow-hidden" style="background: linear-gradient(135deg, #1E5A96 0%, #164575 50%, #0d2d4d 100%);">
    <div class="container mx-auto px-6 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-16">
            
            {{-- Column 1: Brand --}}
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white">{{ $brand['name'] ?? 'Sottana Service' }}</h2>
                <p class="text-white font-semibold uppercase text-xs tracking-widest">{{ $brand['subtitle'] ?? 'Consulenza Sicurezza e Radioprotezione' }}</p>
                <p class="text-gray-100 text-sm leading-relaxed">{{ $brand['description'] ?? '' }}</p>
                
                {{-- Social Icons --}}
                @if(!empty($social))
                 <div class="flex gap-3 pt-2">
                     @if(!empty($social['linkedin']))
                     <a href="{{ $social['linkedin'] }}" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        aria-label="Seguici su LinkedIn (si apre in una nuova finestra)"
                        class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white">
                        <span class="sr-only">LinkedIn</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    @endif
                    @if(!empty($social['facebook']))
                     <a href="{{ $social['facebook'] }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        aria-label="Seguici su Facebook (si apre in una nuova finestra)"
                        class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-blue-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if(!empty($social['instagram']))
                     <a href="{{ $social['instagram'] }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        aria-label="Seguici su Instagram (si apre in una nuova finestra)"
                        class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pink-600 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif
                </div>
                @endif
            </div>
            
            {{-- Column 2: Normative & Certificazioni --}}
            <div>
                <h3 class="text-lg font-bold mb-5 text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    {{ $normative['title'] ?? 'Normative & Certificazioni' }}
                </h3>
                <div class="space-y-4">
                    @foreach($normative['items'] ?? [] as $item)
                    <div class="group">
                        @if(is_array($item))
                            <h4 class="font-semibold text-sm text-white group-hover:text-orange-200 transition-colors">{{ $item['label'] ?? '' }}</h4>
                            @if(!empty($item['description']))
                            <p class="text-xs text-gray-100 mt-1 leading-relaxed border-l-2 border-orange-500/50 pl-3">{{ $item['description'] }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-100 group-hover:text-white transition-colors">{{ $item }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            
            {{-- Column 3: Servizi --}}
            <div>
                <h3 class="text-lg font-bold mb-5 text-white">{{ $services['title'] ?? 'Servizi' }}</h3>
                <ul class="space-y-3">
                    @foreach($services['items'] ?? [] as $item)
                    <li class="flex items-center gap-2 group">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full group-hover:scale-150 transition-transform" aria-hidden="true"></span>
                        <span class="text-sm text-gray-100 group-hover:text-white transition-colors">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- Column 4: Contatti --}}
            <div>
                <h3 class="text-lg font-bold mb-5 text-white">{{ $contact['title'] ?? 'Contatti' }}</h3>
                <ul class="space-y-4">
                    @foreach($contact['items'] ?? [] as $item)
                    <li class="flex items-start gap-3">
                        @php $itemType = is_array($item) ? ($item['type'] ?? '') : ''; @endphp
                        @switch($itemType)
                            @case('address')
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                @break
                            @case('email')
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                @break
                            @case('phone')
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                @break
                            @case('phone_mobile')
                            @case('mobile')
                                <svg class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                @break
                            @default
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                        @endswitch
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-100">
                                @if(isset($item['label']) && $item['label'])
                                    <span class="text-xs text-green-300 font-semibold">{{ $item['label'] }}: </span>
                                @endif
                                {{ is_array($item) ? ($item['value'] ?? '') : $item }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                
                {{-- P.IVA & REA --}}
                @if(!empty($contact['piva']) || !empty($contact['rea']))
                <div class="mt-4 pt-4 border-t border-white/30 text-xs text-gray-100">
                    @if(!empty($contact['piva']))<p class="text-gray-100">P.IVA: {{ $contact['piva'] }}</p>@endif
                    @if(!empty($contact['rea']))<p class="text-gray-100">REA: {{ $contact['rea'] }}</p>@endif
                </div>
                @endif

                {{-- Mappa Statica (gratuita) --}}
                <div class="mt-4">
                    <a href="https://www.google.com/maps/search/?api=1&query=Via+Vanzo+86,+Mogliano+Veneto+TV" 
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Apri mappa posizione su Google Maps (si apre in una nuova finestra)"
                       class="block w-full h-32 rounded-lg overflow-hidden border border-white/20 hover:border-white/40 transition-colors relative group focus:outline-none focus:ring-2 focus:ring-white">
                        <img 
                            src="{{ asset('modules/techplanner/images/map-via-vanzo.png') }}"
                            alt="Mappa posizione: Via Vanzo 86, Mogliano Veneto"
                            class="w-full h-full object-cover grayscale-[30%] contrast-[1.1] group-hover:grayscale-0 transition-all duration-500"
                            onerror="this.src='https://placehold.co/400x150?text=Mappa+Via+Vanzo+86'"
                        >
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors flex items-center justify-center">
                            <span class="text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity bg-blue-600 px-2 py-1 rounded">Vedi su Google Maps</span>
                        </div>
                    </a>
                    <p class="text-xs text-gray-100 mt-1">Clicca per aprire la mappa</p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bottom Bar --}}
    <div class="border-t border-white/20 bg-[#0F3460]">
        <div class="container mx-auto px-6 py-5">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-100">
                <p>{{ $legal['copyright'] ?? '© 2026 Sottana Service' }}</p>
                @if(!empty($legal['links']))
                <div class="flex items-center gap-6">
                    @foreach($legal['links'] as $link)
                    <a href="{{ $link['url'] ?? '#' }}" 
                       class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#0F3460] rounded-md px-1">{{ $link['label'] ?? '' }}</a>
                    @endforeach
                </div>
                @endif
                
                {{-- Back to Top --}}
                <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        class="p-2 rounded-full bg-white/5 hover:bg-white/10 transition-colors group border border-white/10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-[#0F3460]"
                        aria-label="Torna all'inizio della pagina">
                    <svg class="w-4 h-4 text-white/95 group-hover:text-white group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</footer>
