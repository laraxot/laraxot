@props(['title' => null, 'description' => null, 'bodyPage' => ''])

{{--
    Head centralizzato del modulo Cms (<x-cms::head>, laravel/head): SEO, canonical, hreflang,
    JSON-LD, icone e theme-color vengono da la'. Sixteen aggiunge solo cio' che e' suo:
    la registrazione Alpine `mobileMenu` e la guardia FOUC critica, via stack `head`/`styles`
    (story Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md).
--}}
@push('head')
    {{--
        Alpine component registration bootstrap.
        Vite app.js e' type="module" defer → arriva dopo Alpine.
        alpine:init si attiva durante il boot di Alpine (dentro @livewireScripts),
        quindi questo listener deve essere registrato PRIMA.
        Pattern: canonico Alpine, identico alla dark-mode anti-FOUC nel componente Head.
    --}}
    <script>
        document.addEventListener('alpine:init', function () {
            Alpine.data('mobileMenu', () => ({
                isOpen: false,
                isMobileBreakpoint: false,
                init() {
                    this.checkBreakpoint();
                    window.addEventListener('resize', () => this.checkBreakpoint());
                },
                toggle() { this.isOpen = !this.isOpen; },
                open() { this.isOpen = true; },
                close() { this.isOpen = false; },
                checkBreakpoint() {
                    this.isMobileBreakpoint = window.innerWidth < 768;
                    if (!this.isMobileBreakpoint && this.isOpen) this.close();
                },
                isMobile() { return this.isMobileBreakpoint; },
                closeOnItemClick() { if (this.isMobileBreakpoint) this.close(); },
            }));
        });
    </script>
@endpush
@push('styles')
    {{-- Critical FOUC guard: Vite CSS ~1.4MB; senza questo #head-section resta trasparente 300–600ms --}}
    <style id="sixteen-critical-fouc">
        #head-section.bg-primary { background-color: #007a52 !important; }
        #head-section #welcome-heading,
        #head-section #fc-home-hero-heading,
        #head-section .lead,
        #head-section .title-xsmall-semi-bold,
        #head-section .title-xxxlarge { color: #ffffff !important; }
        #head-section .btn-hero-primary { background:#fff !important; color:#007a52 !important; border:0 !important; }
        #head-section .btn-hero-secondary { background:rgb(255 255 255 / 14%) !important; color:#fff !important; border:2px solid #fff !important; }
        [x-cloak] { display: none !important; }
    </style>
@endpush

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    {{-- Verde istituzionale dell'intestazione (#007a52): colora la barra del browser sul telefono. --}}
    <x-cms::head
        :title="$title"
        :description="$description"
        :homepage="$bodyPage === 'homepage'"
        theme-color="#007a52"
    />
    <body @if($bodyPage !== '') data-page="{{ $bodyPage }}" @endif>

        {{-- Messaggio flash di sessione (`status`), es. "account eliminato" dopo il logout: prima solo il layout legacy lo
             mostrava. La chiave e' nel namespace del modulo che l'ha generata (gdpr::account.flash.account_deleted). --}}
        @php
            // Si mostra solo testo leggibile: una chiave tradotta o una frase. Codici come `verification-link-sent` restano
            // alle pagine che li gestiscono.
            $layoutFlash = session('status');
            $layoutFlashMessage = null;
            if (is_string($layoutFlash) && $layoutFlash !== '') {
                $layoutFlashTranslated = __($layoutFlash);
                if (is_string($layoutFlashTranslated) && $layoutFlashTranslated !== $layoutFlash) {
                    $layoutFlashMessage = $layoutFlashTranslated;
                } elseif (str_contains($layoutFlash, ' ')) {
                    $layoutFlashMessage = $layoutFlash;
                }
            }
        @endphp
        @if (is_string($layoutFlashMessage) && $layoutFlashMessage !== '')
            <div x-data="{ open: true }" x-show="open" role="status" aria-live="polite"
                 class="fixed inset-x-4 bottom-4 z-50 mx-auto flex max-w-xl items-start gap-3 rounded-lg border border-green-700 bg-white p-4 text-gray-900 shadow-lg">
                <p class="flex-1">{{ $layoutFlashMessage }}</p>
                <button type="button" class="font-semibold text-green-800 underline" x-on:click="open = false">
                    {{ __('pub_theme::ui.close') }}
                </button>
            </div>
        @endif

        {{ $slot }}
        @livewireScripts
        @filamentScripts(withCore: true)
        @vite(['resources/js/app.js'], 'themes/Sixteen')


    </body>
</html>
