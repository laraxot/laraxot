{{--
    Breadcrumb Navigation AGID
    Navigazione breadcrumb conforme alle Linee Guida PA per i servizi digitali

    @param array $items - Array di elementi breadcrumb
    @param bool $showHome - Mostra link alla home
    @param string|null $currentPage - Pagina corrente (opzionale se nell'array)
    @param bool $structured - Aggiunge structured data Schema.org
--}}

@props([
    'items' => [],
    'showHome' => true,
    'currentPage' => null,
    'structured' => true,
])

@php
    $breadcrumbs = collect($items);

    // Se showHome è true, aggiungi la home come primo elemento
    if ($showHome && !$breadcrumbs->contains('url', '/')) {
        $breadcrumbs->prepend([
            'title' => 'Home',
            'url' => '/',
            'icon' => 'heroicon-o-home'
        ]);
    }

    // Se currentPage è specificata, aggiungila come ultimo elemento senza URL
    if ($currentPage && !$breadcrumbs->last()['current'] ?? false) {
        $breadcrumbs->push([
            'title' => $currentPage,
            'url' => null,
            'current' => true
        ]);
    }
@endphp

@if($breadcrumbs->isNotEmpty())
    <nav class="agid-breadcrumb"
         role="navigation"
         aria-label="Percorso di navigazione"
         {{ $structured ? 'itemscope itemtype="https://schema.org/BreadcrumbList"' : '' }}>

        <div class="agid-breadcrumb__container agid-container">
            <ol class="agid-breadcrumb__list" role="list">
                @foreach($breadcrumbs as $index => $item)
                    @php
                        $isLast = $loop->last;
                        $isCurrent = $item['current'] ?? $isLast;
                        $hasUrl = !empty($item['url']) && !$isCurrent;
                    @endphp

                    <li class="agid-breadcrumb__item {{ $isCurrent ? 'agid-breadcrumb__item--current' : '' }}"
                        role="listitem"
                        {{ $structured ? 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"' : '' }}>

                        @if($hasUrl)
                            <a href="{{ $item['url'] }}"
                               class="agid-breadcrumb__link agid-focus"
                               {{ $structured ? 'itemprop="item"' : '' }}
                               {{ $index === 0 && isset($item['icon']) ? 'aria-label="Torna alla homepage"' : '' }}>

                                @if($index === 0 && isset($item['icon']))
                                    @svg($item['icon'], 'agid-breadcrumb__icon')
                                    <span class="sr-only">Home - </span>
                                @endif

                                <span {{ $structured ? 'itemprop="name"' : '' }}>
                                    {{ $item['title'] ?? $item['name'] ?? 'Pagina' }}
                                </span>
                            </a>
                        @else
                            <span class="agid-breadcrumb__current"
                                  {{ $structured ? 'itemprop="name"' : '' }}
                                  aria-current="page">
                                {{ $item['title'] ?? $item['name'] ?? 'Pagina corrente' }}
                            </span>
                        @endif

                        {{-- Structured data position --}}
                        @if($structured)
                            <meta itemprop="position" content="{{ $index + 1 }}" />
                        @endif

                        {{-- Separator --}}
                        @if(!$isLast)
                            <span class="agid-breadcrumb__separator" aria-hidden="true" role="presentation">
                                @svg('heroicon-o-chevron-right', 'agid-breadcrumb__separator-icon')
                            </span>
                        @endif
                    </li>
                @endforeach
            </ol>

            {{-- Mobile dropdown version per spazi ridotti --}}
            <div class="agid-breadcrumb__mobile" role="navigation" aria-label="Navigazione mobile">
                @if($breadcrumbs->count() > 1)
                    @php $previousPage = $breadcrumbs->get($breadcrumbs->count() - 2); @endphp
                    <a href="{{ $previousPage['url'] ?? '#' }}"
                       class="agid-breadcrumb__back-button agid-focus"
                       aria-label="Torna a {{ $previousPage['title'] ?? 'pagina precedente' }}">
                        @svg('heroicon-o-arrow-left', 'agid-breadcrumb__back-icon')
                        <span class="agid-breadcrumb__back-text">
                            {{ Str::limit($previousPage['title'] ?? 'Indietro', 20) }}
                        </span>
                    </a>
                @endif

                <span class="agid-breadcrumb__current-mobile" aria-current="page">
                    {{ Str::limit($breadcrumbs->last()['title'] ?? 'Pagina corrente', 30) }}
                </span>
            </div>
        </div>
    </nav>
@endif

<style>
{{-- Stili CSS per Breadcrumb AGID --}}
.agid-breadcrumb {
    @apply bg-gray-50 border-b border-gray-200 py-3 md:py-4;
}

.agid-breadcrumb__container {
    @apply flex items-center;
}

.agid-breadcrumb__list {
    @apply hidden md:flex items-center flex-wrap gap-1 text-sm;
}

.agid-breadcrumb__item {
    @apply flex items-center;
}

.agid-breadcrumb__link {
    @apply text-gray-600 hover:text-primary-700 transition-colors duration-200;
    @apply font-medium no-underline hover:underline;
    @apply py-1 px-2 rounded-sm;
}

.agid-breadcrumb__link:focus {
    @apply text-primary-700;
}

.agid-breadcrumb__icon {
    @apply w-4 h-4 mr-1;
}

.agid-breadcrumb__current {
    @apply text-gray-900 font-semibold px-2 py-1;
}

.agid-breadcrumb__separator {
    @apply flex items-center mx-1 text-gray-400;
}

.agid-breadcrumb__separator-icon {
    @apply w-4 h-4;
}

.agid-breadcrumb__mobile {
    @apply flex md:hidden items-center justify-between w-full gap-4;
}

.agid-breadcrumb__back-button {
    @apply flex items-center text-gray-600 hover:text-primary-700 font-medium;
    @apply transition-colors duration-200 no-underline hover:underline;
    @apply py-2 px-3 rounded-md -ml-3;
}

.agid-breadcrumb__back-icon {
    @apply w-4 h-4 mr-2 flex-shrink-0;
}

.agid-breadcrumb__back-text {
    @apply flex-shrink-0;
}

.agid-breadcrumb__current-mobile {
    @apply text-gray-900 font-semibold text-right flex-1 truncate;
    @apply px-3 py-2;
}

/* States and variants */
.agid-breadcrumb__item--current .agid-breadcrumb__current {
    @apply bg-primary-50 text-primary-800 border border-primary-200 rounded-md;
}

/* Dark mode */
.dark .agid-breadcrumb {
    @apply bg-gray-800 border-gray-700;
}

.dark .agid-breadcrumb__link {
    @apply text-gray-300 hover:text-primary-400;
}

.dark .agid-breadcrumb__current {
    @apply text-white;
}

.dark .agid-breadcrumb__separator {
    @apply text-gray-500;
}

.dark .agid-breadcrumb__back-button {
    @apply text-gray-300 hover:text-primary-400;
}

.dark .agid-breadcrumb__current-mobile {
    @apply text-white;
}

.dark .agid-breadcrumb__item--current .agid-breadcrumb__current {
    @apply bg-primary-900 text-primary-200 border-primary-800;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .agid-breadcrumb__back-text {
        @apply hidden;
    }

    .agid-breadcrumb__back-button {
        @apply min-w-[44px] justify-center;
    }

    .agid-breadcrumb__current-mobile {
        @apply text-base;
    }
}

/* Accessibility enhancements */
@media (prefers-reduced-motion: reduce) {
    .agid-breadcrumb__link,
    .agid-breadcrumb__back-button {
        @apply transition-none;
    }
}

@media (prefers-contrast: high) {
    .agid-breadcrumb {
        @apply border-b-2;
    }

    .agid-breadcrumb__link {
        @apply underline font-bold;
    }

    .agid-breadcrumb__current {
        @apply font-bold;
    }
}

/* Print styles */
@media print {
    .agid-breadcrumb {
        @apply bg-transparent border-b border-gray-300 py-2;
    }

    .agid-breadcrumb__mobile {
        @apply hidden;
    }

    .agid-breadcrumb__list {
        @apply flex text-xs;
    }

    .agid-breadcrumb__link {
        @apply text-black no-underline;
    }

    .agid-breadcrumb__current {
        @apply text-black font-bold;
    }

    .agid-breadcrumb__separator {
        @apply text-black;
    }
}

/* Large screens optimization */
@media (min-width: 1280px) {
    .agid-breadcrumb__list {
        @apply text-base;
    }
}

/* Touch device optimizations */
@media (hover: none) and (pointer: coarse) {
    .agid-breadcrumb__link,
    .agid-breadcrumb__back-button {
        @apply py-3 px-4 text-base;
        min-height: 44px;
    }
}

/* RTL Support */
[dir="rtl"] .agid-breadcrumb__list {
    @apply flex-row-reverse;
}

[dir="rtl"] .agid-breadcrumb__separator-icon {
    @apply rotate-180;
}

[dir="rtl"] .agid-breadcrumb__back-icon {
    @apply rotate-180 ml-2 mr-0;
}

[dir="rtl"] .agid-breadcrumb__mobile {
    @apply flex-row-reverse;
}

[dir="rtl"] .agid-breadcrumb__current-mobile {
    @apply text-left;
}
</style>

{{-- JavaScript per funzionalità avanzate --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione keyboard navigation
    const breadcrumbLinks = document.querySelectorAll('.agid-breadcrumb__link, .agid-breadcrumb__back-button');

    breadcrumbLinks.forEach(link => {
        link.addEventListener('keydown', function(e) {
            // Supporto per navigazione con frecce
            if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                e.preventDefault();
                const links = Array.from(breadcrumbLinks);
                const currentIndex = links.indexOf(this);
                let nextIndex;

                if (e.key === 'ArrowRight') {
                    nextIndex = currentIndex + 1;
                } else {
                    nextIndex = currentIndex - 1;
                }

                if (links[nextIndex]) {
                    links[nextIndex].focus();
                }
            }
        });
    });

    // Analytics tracking per breadcrumb clicks
    breadcrumbLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Implementa qui il tracking analytics
            const breadcrumbPath = this.getAttribute('href');
            const breadcrumbText = this.querySelector('span')?.textContent || this.textContent;

            if (typeof gtag !== 'undefined') {
                gtag('event', 'breadcrumb_click', {
                    'breadcrumb_path': breadcrumbPath,
                    'breadcrumb_text': breadcrumbText.trim()
                });
            }
        });
    });

    // Truncation intelligente per mobile
    function adjustMobileBreadcrumb() {
        const currentMobile = document.querySelector('.agid-breadcrumb__current-mobile');
        if (currentMobile && window.innerWidth <= 640) {
            const maxLength = window.innerWidth < 400 ? 20 : 30;
            const originalText = currentMobile.dataset.originalText || currentMobile.textContent;
            currentMobile.dataset.originalText = originalText;

            if (originalText.length > maxLength) {
                currentMobile.textContent = originalText.substring(0, maxLength - 3) + '...';
                currentMobile.title = originalText;
            }
        }
    }

    // Esegui al caricamento e al resize
    adjustMobileBreadcrumb();
    window.addEventListener('resize', adjustMobileBreadcrumb);
});
</script>
@endpush