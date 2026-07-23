@props(['data' => []])

{{-- 
    Contact Section Component - Design Comuni Style
    Usage: <x-blocks.contact-section :data="$contactData" />
    
    Data structure:
    - title: string
    - contact_options: array
    - quick_links: array
--}}

@php
    $title = $data['title'] ?? 'Contatta il Comune';
    $contactOptions = $data['contact_options'] ?? [
        ['label' => 'FAQ', 'url' => '/it/tests/domande-frequenti', 'icon' => 'heroicon-o-question-mark-circle'],
        ['label' => 'Assistenza', 'url' => '/it/tests/assistenza', 'icon' => 'heroicon-o-lifebuoy'],
        ['label' => 'Numero Verde 05 0505', 'url' => 'tel:050505', 'icon' => 'heroicon-o-phone'],
        ['label' => 'Appuntamento', 'url' => '/it/tests/appuntamento', 'icon' => 'heroicon-o-calendar'],
    ];
    $quickLinks = $data['quick_links'] ?? [
        ['label' => 'CIE', 'url' => '#'],
        ['label' => 'Cambio residenza', 'url' => '#'],
        ['label' => 'Tasse', 'url' => '#'],
        ['label' => 'Appuntamenti', 'url' => '#'],
        ['label' => 'Tessera elettorale', 'url' => '#'],
        ['label' => 'Buono connettività', 'url' => '#'],
    ];
@endphp

<section class="contact-section py-8">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
            </div>
        </div>
        
        <div class="row g-4">
            {{-- Contact Options --}}
            <div class="col-12 col-md-6">
                <h3 class="h5 font-semibold mb-4">Come possiamo aiutarti?</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($contactOptions as $option)
                    <a href="{{ $option['url'] }}" class="block p-4 border border-gray-200 rounded-lg hover:border-primary hover:shadow-sm transition-all text-center">
                        <x-filament::icon :icon="$option['icon']" class="w-8 h-8 text-primary mx-auto mb-2" />
                        <span class="text-sm font-medium">{{ $option['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            
            {{-- Quick Links --}}
            <div class="col-12 col-md-6">
                <h3 class="h5 font-semibold mb-4">Link rapidi</h3>
                <ul class="space-y-2">
                    @foreach($quickLinks as $link)
                    <li>
                        <a href="{{ $link['url'] }}" class="text-primary hover:text-primary-dark hover:underline inline-flex items-center gap-2">
                            <x-filament::icon icon="heroicon-m-arrow-right" class="w-4 h-4" />
                            {{ $link['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
