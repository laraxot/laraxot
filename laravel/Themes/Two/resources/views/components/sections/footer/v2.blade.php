@php
    /**
     * Footer Component v1 - WCAG 2.1 AA Compliant
     * 
     * Receives $blocks as DataCollection<BlockData> from Section component.
     * Each BlockData has: type, slug, data, view
     */
    
    // Extract footer data from DataCollection with fallback chain
    $footerBlock = null;
    if (isset($blocks) && !empty($blocks)) {
        foreach ($blocks as $block) {
            // BlockData properties: type, slug, data, view
            if ($block->type === 'footer' && $block->slug === 'main-footer') {
                $footerBlock = $block->data;
                break;
            }
        }
    }

    // Fallback: if no block found, use empty array
    if (!is_array($footerBlock)) {
        $footerBlock = [];
    }
    
    // -------------------------------------------------------------------------
    // Data Extraction with Defaults for Resilience
    // -------------------------------------------------------------------------
    
    $brand = $footerBlock['brand'] ?? [
        'name' => 'Marco Sottana',
        'subtitle' => 'Consulenza Sicurezza',
        'description' => 'Specialisti in radioprotezione e sicurezza per studi dentistici e cliniche veterinarie. Partner di fiducia per la conformità normativa.'
    ];
    
    $social = $footerBlock['social'] ?? [];
    
    $normative = $footerBlock['normative'] ?? [
        'title' => 'Normative & Certificazioni',
        'items' => []
    ];
    
    $services = $footerBlock['services'] ?? [
        'title' => 'Servizi',
        'items' => []
    ];
    
    $contact = $footerBlock['contact'] ?? [
        'title' => 'Contatti',
        'items' => [],
        'piva' => null,
        'rea' => null
    ];
    
    $certifications = $footerBlock['certifications'] ?? [
        'title' => 'Certificazioni',
        'items' => []
    ];
    
    $testimonials = $footerBlock['testimonials'] ?? [
        'title' => 'Dicono di Noi',
        'items' => []
    ];
    
    $quickActions = $footerBlock['quick_actions'] ?? [];
    
    $legal = $footerBlock['legal'] ?? [
        'copyright' => '© 2026 Marco Sottana - Consulenza Sicurezza. Tutti i diritti riservati.',
        'links' => []
    ];

    // Helper for Newsletter (default since not in JSON)
    $newsletter = [
        'title' => 'Rimani aggiornato',
        'description' => 'Ricevi novità su normative, sicurezza e consulenza tecnica',
        'button' => 'Iscriviti'
    ];
@endphp

{{-- Main Footer Container with Solid High-Contrast Background --}}
<footer class="bg-[#0b1120] text-white selection:bg-blue-500/30">
    
    {{-- Main 4-Column Section --}}
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            
            {{-- Column 1: Brand & Social --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-white mb-2">{{ $brand['name'] ?? 'Marco Sottana' }}</h2>
                    <p class="text-blue-400 font-semibold uppercase text-xs tracking-widest">{{ $brand['subtitle'] ?? 'Consulenza Sicurezza' }}</p>
                </div>
                
                <p class="text-blue-100/80 text-sm leading-relaxed max-w-sm">
                    {{ $brand['description'] ?? '' }}
                </p>
                
                {{-- Social Icons --}}
                @if(!empty($social))
                <div class="flex gap-4">
                    @foreach($social as $platform => $url)
                        @if(!empty($url))
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all duration-300 group border border-white/10" aria-label="{{ $platform }}">
                            @switch($platform)
                                @case('linkedin')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    @break
                                @case('facebook')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    @break
                                @case('instagram')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    @break
                            @endswitch
                        </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
            
            {{-- Column 2: Normative --}}
            <div>
                <h3 class="text-lg font-bold mb-6 text-orange-500 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    {{ $normative['title'] ?? 'Normative' }}
                </h3>
                <div class="space-y-6">
                    @foreach($normative['items'] ?? [] as $item)
                    <div class="group cursor-default">
                        @if(is_array($item))
                            <h4 class="font-bold text-sm text-white group-hover:text-orange-400 transition-colors">{{ $item['label'] ?? '' }}</h4>
                            @if(!empty($item['description']))
                            <p class="text-xs text-blue-100/70 mt-2 leading-relaxed border-l-2 border-orange-500/30 pl-3">
                                {{ $item['description'] }}
                            </p>
                            @endif
                        @else
                            <p class="text-sm text-slate-300 group-hover:text-white transition-colors">{{ $item }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            
            {{-- Column 3: Services --}}
            <div>
                <h3 class="text-lg font-bold mb-6 text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $services['title'] ?? 'Servizi' }}
                </h3>
                <ul class="space-y-4">
                    @foreach($services['items'] ?? [] as $item)
                    <li class="flex items-center gap-3 group">
                        <div class="w-1.5 h-1.5 bg-green-500 rounded-full group-hover:scale-150 transition-all duration-300"></div>
                        <span class="text-sm text-blue-100/80 group-hover:text-white transition-colors cursor-pointer">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- Column 4: Contact & Quick Actions --}}
            <div class="space-y-8">
                <div>
                    <h3 class="text-lg font-bold mb-6 text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $contact['title'] ?? 'Contatti' }}
                    </h3>
                    <ul class="space-y-4">
                        @foreach($contact['items'] ?? [] as $item)
                        <li class="flex items-start gap-3 group">
                            @php $itemType = is_array($item) ? ($item['type'] ?? '') : ''; @endphp
                            <div class="flex-shrink-0 mt-1 text-green-500 group-hover:scale-110 transition-transform">
                                @switch($itemType)
                                    @case('address')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @break
                                    @case('email')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        @break
                                    @case('phone')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        @break
                                    @default
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endswitch
                            </div>
                            <span class="text-sm text-blue-100/80 group-hover:text-white transition-colors">{{ is_array($item) ? ($item['value'] ?? '') : $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                {{-- Quick Actions --}}
                @if(!empty($quickActions))
                <div class="pt-6 border-t border-white/5 space-y-3">
                    @foreach($quickActions as $key => $action)
                        @if(!empty($action['phone']) || !empty($action['number']) || !empty($action['url']))
                        <a href="{{ $action['url'] ?? ($action['phone'] ?? $action['number'] ? 'tel:' . ($action['phone'] ?? $action['number']) : '#') }}" 
                           class="flex items-center gap-3 p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-colors border border-white/5 group">
                            <div class="text-blue-500 group-hover:scale-110 transition-transform">
                                @if($key === 'call')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                @elseif($key === 'whatsapp')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.149-.67.149-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-blue-100/90 group-hover:text-white transition-colors">{{ $action['label'] ?? 'Link' }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        {{-- Row 2: Certifications Badge Wall --}}
        @if(!empty($certifications['items']))
        <div class="mt-16 pt-12 border-t border-white/5">
            <h4 class="text-center text-blue-100/90 uppercase text-xs tracking-[0.2em] mb-10 font-bold">{{ $certifications['title'] ?? 'Certificazioni' }}</h4>
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-60 hover:opacity-100 transition-opacity duration-500">
                @foreach($certifications['items'] as $cert)
                <div class="flex flex-col items-center gap-3 group">
                    <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center group-hover:bg-blue-600/20 group-hover:scale-110 transition-all">
                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-[11px] font-bold text-blue-100/90 group-hover:text-white transition-colors uppercase">{{ $cert['name'] ?? '' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- Row 3: Testimonials --}}
        @if(!empty($testimonials['items']))
        <div class="mt-16 pt-12 border-t border-white/5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($testimonials['items'] as $testimonial)
                <div class="bg-white/5 p-6 rounded-2xl border border-white/5 relative overflow-hidden group">
                    <svg class="absolute -top-4 -left-4 w-20 h-20 text-white/5 group-hover:text-blue-500/10 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="text-blue-100/80 text-sm italic relative z-10 mb-6">
                        "{{ $testimonial['text'] ?? '' }}"
                    </p>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white shadow-lg">
                            {{ substr($testimonial['author'] ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $testimonial['author'] ?? '' }}</div>
                            <div class="text-[11px] text-blue-100/90 uppercase tracking-wider">{{ $testimonial['role'] ?? '' }} - {{ $testimonial['location'] ?? '' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    {{-- Newsletter Bar --}}
    <div class="bg-blue-600">
        <div class="container mx-auto px-6 py-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="text-center lg:text-left">
                    <h3 class="text-xl font-bold text-white mb-2">{{ $newsletter['title'] }}</h3>
                    <p class="text-blue-100 text-sm">{{ $newsletter['description'] }}</p>
                </div>
                <form class="flex w-full lg:w-auto max-w-md gap-3" x-data="{ sub: false }" @submit.prevent="sub = true; setTimeout(() => sub = false, 3000)">
                    <input type="email" placeholder="La tua email" required class="flex-1 px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all">
                    <button type="submit" class="px-6 py-3 bg-white text-blue-600 rounded-xl font-bold hover:bg-blue-50 transition-colors shadow-lg active:scale-95 duration-200">
                        <span x-show="!sub">{{ $newsletter['button'] }}</span>
                        <span x-show="sub" class="flex items-center gap-2"><svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Iscritto</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Bottom Bar --}}
    <div class="bg-black/40 border-t border-white/5">
        <div class="container mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-blue-100/70 text-xs text-center md:text-left">
                    <p class="mb-2">{{ $legal['copyright'] ?? '' }}</p>
                    <p class="flex gap-4 justify-center md:justify-start">
                        @if(!empty($contact['piva']))<span>P.IVA: {{ $contact['piva'] }}</span>@endif
                        @if(!empty($contact['rea']))<span>REA: {{ $contact['rea'] }}</span>@endif
                    </p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-x-8 gap-y-2">
                    @foreach($legal['links'] ?? [] as $link)
                    <a href="{{ $link['url'] ?? '#' }}" class="text-xs text-blue-100/70 hover:text-white transition-colors underline-offset-4 hover:underline">{{ $link['label'] ?? '' }}</a>
                    @endforeach
                </div>
                
                {{-- Back to Top --}}
                <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition-all group border border-white/10" aria-label="Top">
                    <svg class="w-4 h-4 text-blue-100/70 group-hover:text-white group-hover:-translate-y-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </button>
            </div>
        </div>
    </div>
</footer>