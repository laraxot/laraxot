{{--
    Componente Service Card AGID
    Design System conformi alle Linee Guida per i servizi digitali della PA

    @param string $title - Titolo del servizio
    @param string $description - Descrizione breve del servizio
    @param string|null $image - URL immagine (opzionale)
    @param string|null $url - URL di destinazione
    @param string|null $status - Stato del servizio (active, coming-soon, maintenance)
    @param array $tags - Array di tag/categorie
    @param string|null $icon - Icona del servizio (heroicon)
    @param bool $featured - Se evidenziare il servizio
    @param string|null $deadline - Scadenza se applicabile
--}}

@props([
    'title' => '',
    'description' => '',
    'image' => null,
    'url' => '#',
    'status' => 'active',
    'tags' => [],
    'icon' => null,
    'featured' => false,
    'deadline' => null,
    'accessibility' => true,
])

<div class="agid-service-card {{ $featured ? 'agid-service-card--featured' : '' }} {{ $status === 'maintenance' ? 'agid-service-card--maintenance' : '' }}"
     role="{{ $url && $url !== '#' ? 'link' : 'article' }}"
     {{ $accessibility ? 'aria-describedby="service-' . Str::slug($title) . '-desc"' : '' }}
     {{ $featured ? 'aria-label="Servizio in evidenza: ' . $title . '"' : '' }}>

    {{-- Header con immagine o icona --}}
    <div class="agid-service-card__header">
        @if($image)
            <img src="{{ $image }}"
                 alt="{{ $title }}"
                 class="agid-service-card__image"
                 loading="lazy"
                 width="300"
                 height="200">
        @elseif($icon)
            <div class="agid-service-card__icon-container">
                @svg($icon, 'agid-service-card__icon')
            </div>
        @endif

        {{-- Badge status --}}
        @if($status !== 'active')
            <div class="agid-service-card__status-badge agid-service-card__status-badge--{{ $status }}">
                @switch($status)
                    @case('coming-soon')
                        <span class="sr-only">Stato servizio:</span>
                        Prossimamente
                        @break
                    @case('maintenance')
                        <span class="sr-only">Stato servizio:</span>
                        Manutenzione
                        @break
                @endswitch
            </div>
        @endif

        {{-- Badge featured --}}
        @if($featured)
            <div class="agid-service-card__featured-badge" aria-label="Servizio in evidenza">
                @svg('heroicon-o-star', 'w-4 h-4')
                In evidenza
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="agid-service-card__content">
        {{-- Tags --}}
        @if(!empty($tags))
            <div class="agid-service-card__tags" role="list" aria-label="Categorie servizio">
                @foreach($tags as $tag)
                    <span class="agid-service-card__tag fi-badge fi-badge-secondary" role="listitem">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Title --}}
        <h3 class="agid-service-card__title">
            @if($url && $url !== '#')
                <a href="{{ $url }}"
                   class="agid-service-card__link agid-focus"
                   {{ $status === 'maintenance' ? 'aria-disabled="true"' : '' }}>
                    {{ $title }}
                    <span class="sr-only">
                        @if($status === 'maintenance')
                            - Servizio in manutenzione
                        @else
                            - Accedi al servizio
                        @endif
                    </span>
                </a>
            @else
                {{ $title }}
            @endif
        </h3>

        {{-- Description --}}
        @if($description)
            <p class="agid-service-card__description"
               {{ $accessibility ? 'id="service-' . Str::slug($title) . '-desc"' : '' }}>
                {{ $description }}
            </p>
        @endif

        {{-- Deadline --}}
        @if($deadline)
            <div class="agid-service-card__deadline">
                @svg('heroicon-o-clock', 'w-4 h-4 inline mr-1')
                <span class="font-semibold">Scadenza:</span>
                <time datetime="{{ $deadline }}">
                    {{ \Carbon\Carbon::parse($deadline)->locale('it')->translatedFormat('d F Y') }}
                </time>
            </div>
        @endif
    </div>

    {{-- Footer con CTA --}}
    @if($url && $url !== '#' && $status === 'active')
        <div class="agid-service-card__footer">
            <a href="{{ $url }}"
               class="fi-btn fi-btn-primary agid-service-card__cta"
               {{ $accessibility ? 'aria-describedby="service-' . Str::slug($title) . '-desc"' : '' }}>
                Accedi al servizio
                @svg('heroicon-o-arrow-right', 'w-4 h-4 ml-2')
            </a>
        </div>
    @elseif($status === 'coming-soon')
        <div class="agid-service-card__footer">
            <button class="fi-btn fi-btn-secondary agid-service-card__cta" disabled>
                Disponibile prossimamente
            </button>
        </div>
    @elseif($status === 'maintenance')
        <div class="agid-service-card__footer">
            <div class="agid-service-card__maintenance-notice">
                @svg('heroicon-o-wrench-screwdriver', 'w-4 h-4 mr-2')
                Servizio temporaneamente non disponibile
            </div>
        </div>
    @endif
</div>

<style>
{{-- Stili CSS per la Service Card AGID --}}
.agid-service-card {
    @apply bg-white rounded-lg border border-gray-200 shadow-agid overflow-hidden transition-all duration-200 hover:shadow-agid-md focus-within:ring-2 focus-within:ring-primary-500 focus-within:ring-offset-2;
    max-width: 400px;
}

.agid-service-card:hover {
    @apply -translate-y-1;
}

.agid-service-card--featured {
    @apply border-primary-200 bg-gradient-to-br from-primary-50 to-white;
}

.agid-service-card--maintenance {
    @apply bg-gray-50 border-gray-300;
}

.agid-service-card__header {
    @apply relative overflow-hidden;
}

.agid-service-card__image {
    @apply w-full h-48 object-cover;
}

.agid-service-card__icon-container {
    @apply flex items-center justify-center h-48 bg-gradient-to-br from-primary-100 to-primary-50;
}

.agid-service-card__icon {
    @apply w-16 h-16 text-primary-600;
}

.agid-service-card__status-badge {
    @apply absolute top-3 right-3 px-2 py-1 text-xs font-semibold rounded-full;
}

.agid-service-card__status-badge--coming-soon {
    @apply bg-warning-100 text-warning-800 border border-warning-200;
}

.agid-service-card__status-badge--maintenance {
    @apply bg-gray-100 text-gray-800 border border-gray-200;
}

.agid-service-card__featured-badge {
    @apply absolute top-3 left-3 px-2 py-1 text-xs font-semibold rounded-full bg-primary-600 text-white flex items-center gap-1;
}

.agid-service-card__content {
    @apply p-6;
}

.agid-service-card__tags {
    @apply flex flex-wrap gap-1 mb-3;
}

.agid-service-card__tag {
    @apply text-xs;
}

.agid-service-card__title {
    @apply text-lg font-bold text-gray-900 mb-2 line-clamp-2;
}

.agid-service-card__link {
    @apply text-primary-700 hover:text-primary-900 transition-colors duration-200 no-underline hover:underline;
}

.agid-service-card__link[aria-disabled="true"] {
    @apply text-gray-500 cursor-not-allowed;
    pointer-events: none;
}

.agid-service-card__description {
    @apply text-sm text-gray-600 line-clamp-3 leading-relaxed;
}

.agid-service-card__deadline {
    @apply mt-3 flex items-center text-sm text-warning-700 bg-warning-50 px-3 py-2 rounded-md;
}

.agid-service-card__footer {
    @apply px-6 pb-6;
}

.agid-service-card__cta {
    @apply w-full justify-center;
}

.agid-service-card__maintenance-notice {
    @apply flex items-center justify-center text-sm text-gray-600 bg-gray-100 px-4 py-3 rounded-md;
}

/* Dark mode */
.dark .agid-service-card {
    @apply bg-gray-800 border-gray-700;
}

.dark .agid-service-card--featured {
    @apply border-primary-800 bg-gradient-to-br from-primary-950 to-gray-800;
}

.dark .agid-service-card--maintenance {
    @apply bg-gray-900 border-gray-600;
}

.dark .agid-service-card__title {
    @apply text-white;
}

.dark .agid-service-card__description {
    @apply text-gray-300;
}

.dark .agid-service-card__link {
    @apply text-primary-400 hover:text-primary-300;
}

.dark .agid-service-card__icon-container {
    @apply from-primary-900 to-primary-950;
}

.dark .agid-service-card__icon {
    @apply text-primary-400;
}

/* Accessibility enhancements */
@media (prefers-reduced-motion: reduce) {
    .agid-service-card,
    .agid-service-card__link {
        @apply transition-none;
    }

    .agid-service-card:hover {
        transform: none;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .agid-service-card {
        @apply border-2 border-black;
    }

    .agid-service-card__link {
        @apply underline;
    }
}

/* Print styles */
@media print {
    .agid-service-card {
        @apply shadow-none border border-gray-300 break-inside-avoid;
    }

    .agid-service-card__cta {
        @apply hidden;
    }
}
</style>