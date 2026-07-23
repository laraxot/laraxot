{{--
    CTA Section - Segnalazione (Design Comuni Style)
    TailwindCSS + DaisyUI
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html
--}}

@props([
    'cta' => [],
])

@if (!empty($cta))
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-8">
    <div class="cmp-text-button mt-0">
        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
            {{ __($cta['title'] ?? 'fixcity::segnalazione.cta.title') }}
        </h2>
        <div class="text-wrapper">
            <p class="text-lg text-gray-600 mb-6">
                {{ __($cta['text'] ?? 'fixcity::segnalazione.cta.text') }}
            </p>
        </div>
        <div class="button-wrapper">
            <a href="{{ $cta['button_url'] ?? '/it/segnalazione-crea' }}"
               class="inline-flex items-center justify-center px-8 py-3 bg-italia-blue hover:bg-italia-blue-dark text-white font-semibold rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __($cta['button_text'] ?? 'fixcity::segnalazione.cta.button') }}
            </a>
        </div>
    </div>
</div>
@endif