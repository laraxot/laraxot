{{--
    Hero Section AGID
    Sezione principale per homepage di siti comunali conformi alle Linee Guida PA

    @param string $title - Titolo principale
    @param string|null $subtitle - Sottotitolo (opzionale)
    @param string|null $description - Descrizione estesa
    @param string|null $backgroundImage - Immagine di sfondo
    @param string|null $ctaText - Testo del pulsante CTA
    @param string|null $ctaUrl - URL del pulsante CTA
    @param string|null $secondaryCta - Testo del secondo CTA
    @param string|null $secondaryCtaUrl - URL del secondo CTA
    @param array $quickLinks - Array di link rapidi
    @param bool $showSearch - Mostra barra di ricerca
    @param string $variant - Variante di stile (default, compact, minimal)
--}}

@props([
    'title' => 'Comune di [Nome Comune]',
    'subtitle' => null,
    'description' => null,
    'backgroundImage' => null,
    'ctaText' => 'Tutti i Servizi',
    'ctaUrl' => '#',
    'secondaryCta' => null,
    'secondaryCtaUrl' => '#',
    'quickLinks' => [],
    'showSearch' => true,
    'variant' => 'default'
])

<section class="agid-hero agid-hero--{{ $variant }}"
         role="banner"
         aria-label="Sezione principale homepage">

    {{-- Background --}}
    @if($backgroundImage)
        <div class="agid-hero__background" role="img" aria-label="Immagine di sfondo {{ $title }}">
            <img src="{{ $backgroundImage }}"
                 alt="{{ $title }}"
                 class="agid-hero__bg-image"
                 loading="eager"
                 fetchpriority="high">
            <div class="agid-hero__overlay"></div>
        </div>
    @endif

    <div class="agid-hero__container agid-container">
        <div class="agid-hero__content">

            {{-- Main content --}}
            <div class="agid-hero__main">
                {{-- Subtitle/Category --}}
                @if($subtitle)
                    <p class="agid-hero__subtitle">{{ $subtitle }}</p>
                @endif

                {{-- Main Title --}}
                <h1 class="agid-hero__title">
                    {{ $title }}
                </h1>

                {{-- Description --}}
                @if($description)
                    <div class="agid-hero__description">
                        {!! $description !!}
                    </div>
                @endif

                {{-- Search Bar --}}
                @if($showSearch)
                    <div class="agid-hero__search" role="search" aria-label="Ricerca nel sito">
                        <form action="/ricerca" method="GET" class="agid-hero__search-form">
                            <div class="agid-hero__search-input-group">
                                <label for="hero-search" class="sr-only">
                                    Cerca servizi, informazioni, documenti
                                </label>
                                <input type="search"
                                       id="hero-search"
                                       name="q"
                                       placeholder="Cerca servizi, informazioni, documenti..."
                                       class="fi-input agid-hero__search-input"
                                       autocomplete="off"
                                       aria-describedby="search-help">
                                <button type="submit"
                                        class="fi-btn fi-btn-primary agid-hero__search-button"
                                        aria-label="Avvia ricerca">
                                    @svg('heroicon-o-magnifying-glass', 'w-5 h-5')
                                </button>
                            </div>
                            <div id="search-help" class="agid-hero__search-help sr-only">
                                Premi Invio o clicca sul pulsante per cercare
                            </div>
                        </form>

                        {{-- Suggerimenti di ricerca --}}
                        <div class="agid-hero__search-suggestions" role="region" aria-label="Suggerimenti di ricerca">
                            <span class="agid-hero__suggestions-label">Ricerche frequenti:</span>
                            <div class="agid-hero__suggestions-list">
                                <a href="/servizi/anagrafe" class="agid-hero__suggestion-link">Anagrafe</a>
                                <a href="/servizi/tributi" class="agid-hero__suggestion-link">Tributi</a>
                                <a href="/servizi/edilizia" class="agid-hero__suggestion-link">Edilizia</a>
                                <a href="/bandi" class="agid-hero__suggestion-link">Bandi</a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- CTA Buttons --}}
                @if($ctaText || $secondaryCta)
                    <div class="agid-hero__actions">
                        @if($ctaText && $ctaUrl)
                            <a href="{{ $ctaUrl }}"
                               class="fi-btn fi-btn-primary agid-hero__cta agid-hero__cta--primary">
                                {{ $ctaText }}
                                @svg('heroicon-o-arrow-right', 'w-4 h-4 ml-2')
                            </a>
                        @endif

                        @if($secondaryCta && $secondaryCtaUrl)
                            <a href="{{ $secondaryCtaUrl }}"
                               class="fi-btn fi-btn-secondary agid-hero__cta agid-hero__cta--secondary">
                                {{ $secondaryCta }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Quick Links Sidebar --}}
            @if(!empty($quickLinks))
                <aside class="agid-hero__sidebar" aria-label="Link rapidi">
                    <h2 class="agid-hero__sidebar-title">Accesso Rapido</h2>
                    <nav class="agid-hero__quick-nav">
                        <ul class="agid-hero__quick-links" role="list">
                            @foreach($quickLinks as $link)
                                <li class="agid-hero__quick-item" role="listitem">
                                    <a href="{{ $link['url'] ?? '#' }}"
                                       class="agid-hero__quick-link"
                                       {{ isset($link['external']) && $link['external'] ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
                                        @if(isset($link['icon']))
                                            @svg($link['icon'], 'agid-hero__quick-icon')
                                        @endif
                                        <span class="agid-hero__quick-text">{{ $link['title'] ?? $link['name'] ?? 'Link' }}</span>
                                        @if(isset($link['external']) && $link['external'])
                                            @svg('heroicon-o-arrow-top-right-on-square', 'agid-hero__external-icon')
                                            <span class="sr-only">(apre in nuova finestra)</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </aside>
            @endif
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="agid-hero__scroll-indicator" aria-hidden="true">
        <button class="agid-hero__scroll-button"
                onclick="document.querySelector('#main-content').scrollIntoView({behavior: 'smooth'})"
                aria-label="Scorri per vedere i contenuti">
            @svg('heroicon-o-chevron-down', 'agid-hero__scroll-icon')
        </button>
    </div>
</section>

<style>
{{-- Stili CSS per Hero Section AGID --}}
.agid-hero {
    @apply relative min-h-[80vh] flex items-center justify-center overflow-hidden;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.agid-hero--compact {
    @apply min-h-[60vh];
}

.agid-hero--minimal {
    @apply min-h-[40vh];
}

.agid-hero__background {
    @apply absolute inset-0 z-0;
}

.agid-hero__bg-image {
    @apply w-full h-full object-cover;
}

.agid-hero__overlay {
    @apply absolute inset-0 bg-gradient-to-r from-gray-900/70 to-gray-900/40;
}

.agid-hero__container {
    @apply relative z-10 w-full;
}

.agid-hero__content {
    @apply grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-center;
}

.agid-hero__main {
    @apply lg:col-span-2 text-center lg:text-left;
}

.agid-hero__subtitle {
    @apply text-sm md:text-base font-semibold text-primary-600 uppercase tracking-wide mb-4;
}

.agid-hero [data-has-background] .agid-hero__subtitle {
    @apply text-primary-200;
}

.agid-hero__title {
    @apply text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 mb-6 leading-tight;
}

.agid-hero [data-has-background] .agid-hero__title {
    @apply text-white;
}

.agid-hero__description {
    @apply text-base md:text-lg lg:text-xl text-gray-600 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed;
}

.agid-hero [data-has-background] .agid-hero__description {
    @apply text-gray-200;
}

.agid-hero__search {
    @apply mb-8;
}

.agid-hero__search-form {
    @apply max-w-2xl mx-auto lg:mx-0;
}

.agid-hero__search-input-group {
    @apply flex flex-col sm:flex-row gap-3 mb-4;
}

.agid-hero__search-input {
    @apply flex-1 text-base md:text-lg py-4 px-6 rounded-lg;
}

.agid-hero__search-button {
    @apply sm:w-auto px-6 py-4 text-base md:text-lg;
}

.agid-hero__search-suggestions {
    @apply flex flex-wrap items-center gap-2 text-sm;
}

.agid-hero__suggestions-label {
    @apply font-medium text-gray-700 mr-2;
}

.agid-hero [data-has-background] .agid-hero__suggestions-label {
    @apply text-gray-300;
}

.agid-hero__suggestions-list {
    @apply flex flex-wrap gap-2;
}

.agid-hero__suggestion-link {
    @apply text-primary-600 hover:text-primary-800 font-medium transition-colors duration-200 agid-focus;
}

.agid-hero [data-has-background] .agid-hero__suggestion-link {
    @apply text-primary-300 hover:text-primary-100;
}

.agid-hero__actions {
    @apply flex flex-col sm:flex-row gap-4 justify-center lg:justify-start;
}

.agid-hero__cta {
    @apply text-base md:text-lg py-4 px-8 min-w-[200px] justify-center;
}

.agid-hero__sidebar {
    @apply bg-white/95 backdrop-blur-sm rounded-xl p-6 shadow-agid-lg border border-gray-200;
}

.agid-hero [data-has-background] .agid-hero__sidebar {
    @apply bg-white/90;
}

.agid-hero__sidebar-title {
    @apply text-lg font-bold text-gray-900 mb-4;
}

.agid-hero__quick-links {
    @apply space-y-2;
}

.agid-hero__quick-link {
    @apply flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200 agid-focus;
    @apply text-gray-700 hover:text-primary-700 font-medium;
}

.agid-hero__quick-icon {
    @apply w-5 h-5 text-primary-600 flex-shrink-0;
}

.agid-hero__external-icon {
    @apply w-4 h-4 text-gray-400 ml-auto flex-shrink-0;
}

.agid-hero__scroll-indicator {
    @apply absolute bottom-8 left-1/2 transform -translate-x-1/2;
}

.agid-hero__scroll-button {
    @apply p-3 rounded-full bg-white/80 hover:bg-white shadow-agid transition-all duration-200 agid-focus;
    animation: bounce 2s infinite;
}

.agid-hero__scroll-icon {
    @apply w-6 h-6 text-gray-600;
}

/* Animations */
@keyframes bounce {
    0%, 20%, 53%, 80%, 100% {
        transform: translateY(0);
    }
    40%, 43% {
        transform: translateY(-8px);
    }
    70% {
        transform: translateY(-4px);
    }
    90% {
        transform: translateY(-2px);
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .agid-hero__content {
        @apply grid-cols-1 text-center;
    }

    .agid-hero__sidebar {
        @apply order-first;
    }

    .agid-hero__quick-links {
        @apply grid grid-cols-2 gap-2;
    }
}

/* Dark mode */
.dark .agid-hero {
    background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
}

.dark .agid-hero__title {
    @apply text-white;
}

.dark .agid-hero__description {
    @apply text-gray-300;
}

.dark .agid-hero__suggestions-label {
    @apply text-gray-300;
}

.dark .agid-hero__sidebar {
    @apply bg-gray-800/95 border-gray-700;
}

.dark .agid-hero__sidebar-title {
    @apply text-white;
}

.dark .agid-hero__quick-link {
    @apply text-gray-300 hover:text-primary-400 hover:bg-gray-700;
}

.dark .agid-hero__scroll-button {
    @apply bg-gray-800/80 hover:bg-gray-800;
}

.dark .agid-hero__scroll-icon {
    @apply text-gray-300;
}

/* Accessibility and performance */
@media (prefers-reduced-motion: reduce) {
    .agid-hero__scroll-button {
        animation: none;
    }

    .agid-hero__scroll-button,
    .agid-hero__quick-link,
    .agid-hero__suggestion-link {
        @apply transition-none;
    }
}

@media (prefers-contrast: high) {
    .agid-hero__sidebar {
        @apply border-2 border-gray-800;
    }

    .agid-hero__quick-link:hover {
        @apply bg-gray-200;
    }
}

/* Print styles */
@media print {
    .agid-hero {
        @apply min-h-0 py-8;
        background: white !important;
    }

    .agid-hero__background,
    .agid-hero__scroll-indicator,
    .agid-hero__search,
    .agid-hero__actions {
        @apply hidden;
    }

    .agid-hero__title {
        @apply text-black text-2xl;
    }

    .agid-hero__description {
        @apply text-black;
    }
}
</style>

{{-- JavaScript per funzionalità avanzate --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aggiorna attributo data quando c'è un'immagine di sfondo
    const hero = document.querySelector('.agid-hero');
    const hasBackground = document.querySelector('.agid-hero__background');

    if (hero && hasBackground) {
        hero.setAttribute('data-has-background', 'true');
    }

    // Auto-complete per la ricerca (esempio)
    const searchInput = document.querySelector('#hero-search');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            // Implementa qui la logica di auto-complete
            // collegandoti con le API del comune
        });
    }

    // Keyboard navigation per i quick links
    const quickLinks = document.querySelectorAll('.agid-hero__quick-link');
    quickLinks.forEach(link => {
        link.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
});
</script>
@endpush