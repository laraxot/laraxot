{{--
    CTA Section - "Fai una segnalazione"
    Design Comuni Style with TailwindCSS + DaisyUI
--}}

@php
    $cta = $data['main_content']['cta'] ?? [];
    $title = $cta['title'] ?? 'fixcity::ticket.map.cta.title.label';
    $text = $cta['text'] ?? 'fixcity::ticket.map.cta.text.label';
    $buttonText = $cta['button_text'] ?? 'fixcity::ticket.map.cta.button.label';
    $buttonUrl = $cta['button_url'] ?? '/it/tests/ticket-crea';
@endphp

@if (!empty($cta))
<section class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:p-8 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        {{-- Text Content --}}
        <div class="flex-1">
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
                {{ __($title) }}
            </h2>
            <p class="text-gray-600 text-lg leading-relaxed max-w-2xl">
                {{ __($text) }}
            </p>
        </div>
        
        {{-- CTA Button --}}
        <div class="flex-shrink-0">
            <a href="{{ $buttonUrl }}" 
               class="btn btn-primary btn-lg gap-2 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __($buttonText) }}
            </a>
        </div>
    </div>
</section>
@endif
