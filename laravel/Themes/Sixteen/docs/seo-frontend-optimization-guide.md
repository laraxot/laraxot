# SEO & Frontend Optimization Guide - Sixteen Theme

## Executive Summary

This document provides comprehensive SEO and performance optimization strategies for the **Sixteen Theme**, the citizen-facing frontend of FixCity. These optimizations target Google's 2025 ranking factors and Core Web Vitals to achieve first-page rankings for local civic engagement keywords.

---

## Frontend SEO Architecture

### 1. HTML Semantic Structure

#### Proper Document Structure

```blade
{{-- resources/views/pages/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" prefix="og: http://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Primary Meta Tags --}}
    <title>@yield('title', __('Report Urban Issues')) - {{ config('app.name') }}</title>
    <meta name="title" content="@yield('meta_title', __('Report Urban Issues - :city', ['city' => $municipality->name]))">
    <meta name="description" content="@yield('meta_description', __('Civic reporting platform for :city. Report potholes, broken lights, and other urban issues.', ['city' => $municipality->name]))">
    <meta name="keywords" content="@yield('meta_keywords', 'segnalazioni civiche, buche stradali, illuminazione pubblica, ' . $municipality->name)">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', __('Report Urban Issues - :city', ['city' => $municipality->name]))">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('meta_title')">
    <meta name="twitter:description" content="@yield('meta_description')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/twitter-card.jpg'))">

    {{-- Geo Tags for Local SEO --}}
    <meta name="geo.region" content="IT-{{ $municipality->province_code }}">
    <meta name="geo.placename" content="{{ $municipality->name }}">
    <meta name="geo.position" content="{{ $municipality->lat }};{{ $municipality->lng }}">
    <meta name="ICBM" content="{{ $municipality->lat }}, {{ $municipality->lng }}">

    {{-- Preconnect to required origins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://api.mapbox.com">

    {{-- DNS Prefetch --}}
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">

    {{-- Preload Critical Assets --}}
    <link rel="preload" href="{{ mix('css/app.css', 'themes/sixteen') }}" as="style">
    <link rel="preload" href="{{ mix('js/app.js', 'themes/sixteen') }}" as="script">
    <link rel="preload" href="{{ asset('fonts/titillium-web-v15-latin-regular.woff2') }}" as="font" type="font/woff2" crossorigin>

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css', 'themes/sixteen') }}">

    {{-- Structured Data --}}
    @stack('structured-data')
</head>
<body>
    @yield('content')

    {{-- Scripts (deferred) --}}
    <script src="{{ mix('js/app.js', 'themes/sixteen') }}" defer></script>
    @stack('scripts')
</body>
</html>
```

---

### 2. Core Web Vitals Optimization

#### LCP (Largest Contentful Paint) - Target < 2.5s

**Optimize Hero Images**:
```blade
{{-- Homepage hero section --}}
<section class="hero">
    {{-- Preload hero image --}}
    @push('head')
        <link rel="preload"
              as="image"
              href="{{ asset('images/hero-' . $municipality->slug . '.webp') }}"
              imagesrcset="{{ asset('images/hero-' . $municipality->slug . '-320w.webp') }} 320w,
                           {{ asset('images/hero-' . $municipality->slug . '-640w.webp') }} 640w,
                           {{ asset('images/hero-' . $municipality->slug . '-1280w.webp') }} 1280w"
              imagesizes="100vw">
    @endpush

    <picture>
        <source media="(max-width: 640px)"
                srcset="{{ asset('images/hero-' . $municipality->slug . '-320w.webp') }}">
        <source media="(max-width: 1280px)"
                srcset="{{ asset('images/hero-' . $municipality->slug . '-640w.webp') }}">
        <source media="(min-width: 1281px)"
                srcset="{{ asset('images/hero-' . $municipality->slug . '-1280w.webp') }}">

        <img src="{{ asset('images/hero-' . $municipality->slug . '.webp') }}"
             alt="{{ __('Report urban issues in :city', ['city' => $municipality->name]) }}"
             width="1280"
             height="720"
             fetchpriority="high"
             decoding="async">
    </picture>

    <div class="hero-content">
        <h1>{{ __('Report Urban Issues in :city', ['city' => $municipality->name]) }}</h1>
        <p>{{ __('Help improve your city by reporting problems directly to local authorities') }}</p>
        <a href="{{ route('tickets.create') }}" class="btn-primary">
            {{ __('Report an Issue') }}
        </a>
    </div>
</section>
```

**Optimize Ticket Images**:
```blade
{{-- Ticket card component --}}
<article class="ticket-card">
    @if($ticket->image_url)
        <img src="{{ $ticket->thumbnail_url }}"
             alt="{{ $ticket->title }}"
             width="400"
             height="300"
             loading="{{ $loop->first ? 'eager' : 'lazy' }}"
             decoding="async"
             srcset="{{ $ticket->thumbnail_320_url }} 320w,
                     {{ $ticket->thumbnail_640_url }} 640w"
             sizes="(max-width: 640px) 100vw, 400px">
    @endif

    <div class="ticket-content">
        <h3>
            <a href="{{ route('tickets.show', $ticket) }}">
                {{ $ticket->title }}
            </a>
        </h3>
        <p>{{ Str::limit($ticket->description, 120) }}</p>
    </div>
</article>
```

**Image Optimization Pipeline**:
```php
// app/Services/ImageOptimizationService.php
public function optimizeTicketImage(UploadedFile $image, Ticket $ticket): array
{
    $optimizedPaths = [];

    // Generate WebP versions
    $webp = Image::make($image)
        ->encode('webp', 85)
        ->resize(1200, null, fn($constraint) => $constraint->aspectRatio());

    $webp->save(storage_path("app/public/tickets/{$ticket->id}/image.webp"));
    $optimizedPaths['original'] = "tickets/{$ticket->id}/image.webp";

    // Generate responsive thumbnails
    foreach ([320, 640, 1280] as $width) {
        $thumb = Image::make($image)
            ->encode('webp', 85)
            ->resize($width, null, fn($constraint) => $constraint->aspectRatio());

        $thumb->save(storage_path("app/public/tickets/{$ticket->id}/thumb-{$width}w.webp"));
        $optimizedPaths["thumb_{$width}"] = "tickets/{$ticket->id}/thumb-{$width}w.webp";
    }

    return $optimizedPaths;
}
```

#### INP (Interaction to Next Paint) - Target < 200ms

**Optimize Event Handlers**:
```javascript
// resources/js/components/ticket-list.js
import { debounce } from 'lodash-es';

Alpine.data('ticketList', () => ({
    filters: {
        category: '',
        status: '',
        search: ''
    },

    // Debounce search input
    searchTickets: debounce(function(query) {
        this.filters.search = query;
        this.fetchTickets();
    }, 300),

    // Use requestIdleCallback for non-critical updates
    updateStats() {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                this.fetchStats();
            });
        } else {
            setTimeout(() => this.fetchStats(), 100);
        }
    },

    // Optimize DOM updates with virtual scrolling
    async fetchTickets() {
        const response = await fetch('/api/tickets?' + new URLSearchParams(this.filters));
        const tickets = await response.json();

        // Use DocumentFragment for batch DOM updates
        const fragment = document.createDocumentFragment();
        tickets.forEach(ticket => {
            const card = this.createTicketCard(ticket);
            fragment.appendChild(card);
        });

        this.$refs.ticketList.replaceChildren(fragment);
    }
}));
```

**Reduce JavaScript Execution Time**:
```javascript
// vite.config.js
export default {
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor code
                    'vendor': ['alpinejs', 'axios'],
                    'maps': ['leaflet'],
                    'charts': ['chart.js']
                }
            }
        },
        // Minify and tree-shake
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                pure_funcs: ['console.log']
            }
        }
    }
};
```

#### CLS (Cumulative Layout Shift) - Target < 0.1

**Prevent Layout Shifts**:
```blade
{{-- Reserve space for images --}}
<div class="ticket-image" style="aspect-ratio: 4/3;">
    <img src="{{ $ticket->image_url }}"
         alt="{{ $ticket->title }}"
         width="800"
         height="600"
         loading="lazy">
</div>

{{-- Reserve space for map --}}
<div class="map-container" style="min-height: 400px;">
    <div id="map" data-lat="{{ $ticket->lat }}" data-lng="{{ $ticket->lng }}"></div>
</div>

{{-- Prevent font swap layout shift --}}
@push('head')
    <link rel="preload" as="font" type="font/woff2"
          href="{{ asset('fonts/titillium-web-regular.woff2') }}"
          crossorigin>

    <style>
        @font-face {
            font-family: 'Titillium Web';
            font-style: normal;
            font-weight: 400;
            font-display: swap; /* Prevent FOIT, accept FOUT */
            src: url('{{ asset('fonts/titillium-web-regular.woff2') }}') format('woff2');
        }
    </style>
@endpush
```

**CSS to Prevent Shifts**:
```css
/* resources/css/components/layout.css */

/* Reserve space for async-loaded content */
.skeleton-loader {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    min-height: 200px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Prevent aspect ratio shifts */
.responsive-image {
    aspect-ratio: attr(width) / attr(height);
    width: 100%;
    height: auto;
    object-fit: cover;
}

/* Prevent ad/widget shifts */
.widget-container {
    min-height: 250px;
    contain: layout;
}
```

---

### 3. Advanced SEO Meta Tags

#### Dynamic Page Titles & Descriptions

```blade
{{-- Ticket detail page --}}
@section('title', $ticket->title . ' - ' . $ticket->city)

@section('meta_description')
    {{ __(':category reported in :location on :date. Status: :status. :description',
        [
            'category' => $ticket->category->name,
            'location' => $ticket->address,
            'date' => $ticket->created_at->format('d/m/Y'),
            'status' => $ticket->status->label(),
            'description' => Str::limit($ticket->description, 80)
        ]
    ) }}
@endsection

@section('meta_keywords')
    {{ implode(', ', [
        $ticket->category->slug,
        $ticket->city,
        $ticket->province,
        'segnalazione civica',
        'segnala problema comune',
        str_replace('-', ' ', $ticket->category->slug) . ' ' . $ticket->city
    ]) }}
@endsection

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Report",
    "headline": "{{ $ticket->title }}",
    "description": "{{ $ticket->description }}",
    "image": "{{ $ticket->image_url }}",
    "datePublished": "{{ $ticket->created_at->toIso8601String() }}",
    "dateModified": "{{ $ticket->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $ticket->owner->name }}",
        "url": "{{ route('profile.show', $ticket->owner) }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo-512x512.png') }}"
        }
    },
    "contentLocation": {
        "@type": "Place",
        "name": "{{ $ticket->address }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $ticket->address }}",
            "addressLocality": "{{ $ticket->city }}",
            "addressRegion": "{{ $ticket->province }}",
            "postalCode": "{{ $ticket->postal_code }}",
            "addressCountry": "IT"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "{{ $ticket->lat }}",
            "longitude": "{{ $ticket->lng }}"
        }
    },
    "about": {
        "@type": "Thing",
        "name": "{{ $ticket->category->name }}",
        "url": "{{ route('categories.show', $ticket->category) }}"
    }
}
</script>

{{-- Breadcrumb Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ route('home') }}"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "{{ $ticket->category->name }}",
            "item": "{{ route('categories.show', $ticket->category) }}"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $ticket->city }}",
            "item": "{{ route('tickets.index', ['city' => $ticket->city]) }}"
        },
        {
            "@type": "ListItem",
            "position": 4,
            "name": "{{ $ticket->title }}",
            "item": "{{ route('tickets.show', $ticket) }}"
        }
    ]
}
</script>
@endpush
```

#### Category Landing Pages SEO

```blade
{{-- resources/views/pages/categories/[category].blade.php --}}
@php
    use function Laravel\Folio\name;
    name('categories.show');
@endphp

@section('title', __(':category Reports in :city', [
    'category' => $category->name,
    'city' => $municipality->name
]))

@section('meta_description', __(
    'View and report :category issues in :city. :count active reports. Average resolution time: :days days.',
    [
        'category' => strtolower($category->name),
        'city' => $municipality->name,
        'count' => $stats['active'],
        'days' => $stats['avg_resolution_days']
    ]
))

@push('structured-data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "{{ $category->name }} - {{ $municipality->name }}",
    "description": "{{ __('Active :category reports in :city', ['category' => $category->name, 'city' => $municipality->name]) }}",
    "url": "{{ route('categories.show', $category) }}",
    "mainEntity": {
        "@type": "ItemList",
        "itemListElement": [
            @foreach($tickets as $index => $ticket)
            {
                "@type": "ListItem",
                "position": {{ $index + 1 }},
                "item": {
                    "@type": "Report",
                    "name": "{{ $ticket->title }}",
                    "url": "{{ route('tickets.show', $ticket) }}",
                    "image": "{{ $ticket->image_url }}",
                    "datePublished": "{{ $ticket->created_at->toIso8601String() }}"
                }
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
    }
}
</script>

{{-- FAQ Schema for Category --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "{{ __('How to report :category in :city?', ['category' => strtolower($category->name), 'city' => $municipality->name]) }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ __('To report :category, click New Report, select :category category, add location and photo, then submit.', ['category' => strtolower($category->name)]) }}"
            }
        },
        {
            "@type": "Question",
            "name": "{{ __('How long does it take to fix :category?', ['category' => strtolower($category->name)]) }}",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{ __('Average resolution time for :category in :city is :days days.', ['category' => strtolower($category->name), 'city' => $municipality->name, 'days' => $stats['avg_resolution_days']]) }}"
            }
        }
    ]
}
</script>
@endpush

<article>
    {{-- SEO-optimized heading --}}
    <h1>{{ __(':category Reports - :city', ['category' => $category->name, 'city' => $municipality->name]) }}</h1>

    {{-- Intro paragraph with keywords --}}
    <div class="intro">
        <p>
            {{ __('Report :category issues in :city using our civic reporting platform. Citizens have reported :total :category problems, with :resolved already resolved by local authorities.',
                [
                    'category' => strtolower($category->name),
                    'city' => $municipality->name,
                    'total' => $stats['total'],
                    'resolved' => $stats['resolved']
                ]
            ) }}
        </p>
    </div>

    {{-- Stats for E-E-A-T --}}
    <section aria-labelledby="stats-heading">
        <h2 id="stats-heading">{{ __('Statistics') }}</h2>
        <div class="stats-grid">
            <div class="stat">
                <strong>{{ $stats['active'] }}</strong>
                <span>{{ __('Active Reports') }}</span>
            </div>
            <div class="stat">
                <strong>{{ $stats['resolved_this_month'] }}</strong>
                <span>{{ __('Resolved This Month') }}</span>
            </div>
            <div class="stat">
                <strong>{{ $stats['avg_resolution_days'] }}</strong>
                <span>{{ __('Avg. Days to Resolve') }}</span>
            </div>
        </div>
    </section>

    {{-- Interactive map for engagement --}}
    <section aria-labelledby="map-heading">
        <h2 id="map-heading">{{ __(':category Map - :city', ['category' => $category->name, 'city' => $municipality->name]) }}</h2>
        <div id="category-map" data-category="{{ $category->id }}"></div>
    </section>

    {{-- Recent tickets for freshness --}}
    <section aria-labelledby="recent-heading">
        <h2 id="recent-heading">{{ __('Recent :category Reports', ['category' => $category->name]) }}</h2>

        <div class="ticket-grid">
            @foreach($tickets as $ticket)
                <x-ticket-card :ticket="$ticket" />
            @endforeach
        </div>

        {{ $tickets->links() }}
    </section>

    {{-- Internal linking --}}
    <section aria-labelledby="related-heading">
        <h2 id="related-heading">{{ __('Related Categories') }}</h2>
        <ul>
            @foreach($relatedCategories as $related)
                <li>
                    <a href="{{ route('categories.show', $related) }}">
                        {{ __('Report :category in :city', ['category' => $related->name, 'city' => $municipality->name]) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- CTA for conversions --}}
    <section class="cta">
        <h2>{{ __('Report :category Issue', ['category' => $category->name]) }}</h2>
        <p>{{ __('Help improve :city by reporting :category problems', ['city' => $municipality->name, 'category' => strtolower($category->name)]) }}</p>
        <a href="{{ route('tickets.create', ['category' => $category->slug]) }}" class="btn-primary">
            {{ __('Report Now') }}
        </a>
    </section>
</article>
```

---

### 4. Performance Optimization

#### Resource Hints

```blade
@push('head')
    {{-- Preconnect to critical origins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://api.mapbox.com">

    {{-- DNS Prefetch for non-critical resources --}}
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">

    {{-- Preload critical resources --}}
    <link rel="preload" href="{{ mix('css/app.css', 'themes/sixteen') }}" as="style">
    <link rel="preload" href="{{ mix('js/app.js', 'themes/sixteen') }}" as="script">
    <link rel="preload" href="{{ asset('fonts/titillium-web-regular.woff2') }}" as="font" type="font/woff2" crossorigin>

    {{-- Prefetch likely next pages --}}
    @if(isset($ticket))
        @foreach($relatedTickets as $related)
            <link rel="prefetch" href="{{ route('tickets.show', $related) }}">
        @endforeach
    @endif
@endpush
```

#### Code Splitting & Lazy Loading

```javascript
// resources/js/app.js
import Alpine from 'alpinejs';

// Core functionality - load immediately
import './components/header';
import './components/footer';

// Lazy load non-critical components
Alpine.data('ticketMap', () => ({
    async init() {
        if (this.$el.dataset.lazy === 'true') {
            const { default: initMap } = await import('./components/map');
            initMap(this.$el);
        }
    }
}));

Alpine.data('ticketGallery', () => ({
    async init() {
        const { default: initGallery } = await import('./components/gallery');
        initGallery(this.$el);
    }
}));

Alpine.start();
```

```blade
{{-- Lazy load map component --}}
<div x-data="ticketMap" data-lazy="true">
    <button @click="$el.dataset.lazy = 'false'; init()">
        {{ __('Load Interactive Map') }}
    </button>
</div>
```

#### Service Worker for Caching

```javascript
// Themes/Sixteen/public/sw.js
const CACHE_VERSION = 'v1.0.0';
const CACHE_NAME = `fixcity-${CACHE_VERSION}`;

const STATIC_ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/fonts/titillium-web-regular.woff2',
    '/images/logo.svg'
];

// Install service worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Activate and clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch strategy: Network first, fallback to cache
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') return;

    // Skip admin and API requests
    if (event.request.url.includes('/admin/') || event.request.url.includes('/api/')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Clone response to cache
                const responseClone = response.clone();
                caches.open(CACHE_NAME).then(cache => {
                    cache.put(event.request, responseClone);
                });
                return response;
            })
            .catch(() => {
                // Fallback to cache
                return caches.match(event.request);
            })
    );
});
```

```blade
{{-- Register service worker --}}
@push('scripts')
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => console.log('SW registered:', reg))
            .catch(err => console.error('SW registration failed:', err));
    });
}
</script>
@endpush
```

---

### 5. Local SEO Implementation

#### Google Business Profile Integration

```blade
{{-- Footer with consistent NAP (Name, Address, Phone) --}}
<footer itemscope itemtype="https://schema.org/GovernmentOrganization">
    <div class="footer-contact">
        <h3 itemprop="name">{{ config('app.name') }}</h3>

        <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <span itemprop="streetAddress">{{ $municipality->address }}</span><br>
            <span itemprop="postalCode">{{ $municipality->postal_code }}</span>
            <span itemprop="addressLocality">{{ $municipality->name }}</span>,
            <span itemprop="addressRegion">{{ $municipality->province }}</span>
        </address>

        <p>
            {{ __('Phone') }}:
            <a href="tel:{{ $municipality->phone }}" itemprop="telephone">
                {{ $municipality->phone }}
            </a>
        </p>

        <p>
            {{ __('Email') }}:
            <a href="mailto:{{ $municipality->email }}" itemprop="email">
                {{ $municipality->email }}
            </a>
        </p>

        <meta itemprop="url" content="{{ config('app.url') }}">
        <meta itemprop="priceRange" content="Gratuito">

        <div itemprop="geo" itemscope itemtype="https://schema.org/GeoCoordinates">
            <meta itemprop="latitude" content="{{ $municipality->lat }}">
            <meta itemprop="longitude" content="{{ $municipality->lng }}">
        </div>

        <link itemprop="logo" href="{{ asset('images/logo-512x512.png') }}">
    </div>

    {{-- Social links --}}
    <div class="footer-social">
        <a href="{{ $municipality->facebook_url }}" rel="noopener" itemprop="sameAs">Facebook</a>
        <a href="{{ $municipality->twitter_url }}" rel="noopener" itemprop="sameAs">Twitter</a>
        <a href="{{ $municipality->instagram_url }}" rel="noopener" itemprop="sameAs">Instagram</a>
    </div>
</footer>
```

#### City-Specific Landing Pages

```blade
{{-- resources/views/pages/cities/[city].blade.php --}}
@section('title', __('Report Issues in :city - Civic Reporting Platform', ['city' => $city->name]))

@section('meta_description', __(
    'Report urban issues in :city: potholes, broken lights, trash. :active active reports, :resolved resolved. Average response: :days days.',
    [
        'city' => $city->name,
        'active' => $stats['active'],
        'resolved' => $stats['resolved'],
        'days' => $stats['avg_response_days']
    ]
))

<article>
    <h1>{{ __('Civic Reporting - :city', ['city' => $city->name]) }}</h1>

    {{-- Keyword-rich intro --}}
    <div class="city-intro">
        <p>
            {{ __('Welcome to the official civic reporting platform for :city. Report potholes, broken street lights, abandoned trash, and other urban issues directly to the municipality.',
                ['city' => $city->name]
            ) }}
        </p>
    </div>

    {{-- Category grid with local keywords --}}
    <section aria-labelledby="categories-heading">
        <h2 id="categories-heading">{{ __('What Can You Report in :city?', ['city' => $city->name]) }}</h2>

        <div class="category-grid">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', ['category' => $category, 'city' => $city]) }}"
                   class="category-card">
                    <h3>{{ $category->name }}</h3>
                    <p>
                        {{ __(':count active reports', ['count' => $category->active_count]) }}
                    </p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- City-specific stats for E-A-T --}}
    <section aria-labelledby="city-stats-heading">
        <h2 id="city-stats-heading">{{ __(':city Statistics', ['city' => $city->name]) }}</h2>

        <div class="stats-grid">
            <div class="stat">
                <strong>{{ $stats['total_reports'] }}</strong>
                <span>{{ __('Total Reports') }}</span>
            </div>
            <div class="stat">
                <strong>{{ $stats['resolved_percentage'] }}%</strong>
                <span>{{ __('Resolution Rate') }}</span>
            </div>
            <div class="stat">
                <strong>{{ $stats['active_citizens'] }}</strong>
                <span>{{ __('Active Citizens') }}</span>
            </div>
        </div>
    </section>

    {{-- Neighborhood breakdown for ultra-local SEO --}}
    <section aria-labelledby="neighborhoods-heading">
        <h2 id="neighborhoods-heading">{{ __('Reports by Neighborhood in :city', ['city' => $city->name]) }}</h2>

        <ul class="neighborhood-list">
            @foreach($neighborhoods as $neighborhood)
                <li>
                    <a href="{{ route('neighborhoods.show', $neighborhood) }}">
                        {{ $neighborhood->name }} - {{ __(':count reports', ['count' => $neighborhood->reports_count]) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
</article>
```

---

### 6. Content Freshness Signals


```blade
{{-- Ticket detail page --}}
<article itemscope itemtype="https://schema.org/Report">
    <header>
        <h1 itemprop="headline">{{ $ticket->title }}</h1>

        <div class="meta-info">
            <time itemprop="datePublished" datetime="{{ $ticket->created_at->toIso8601String() }}">
                {{ __('Reported on :date', ['date' => $ticket->created_at->format('d F Y')]) }}
            </time>

            @if($ticket->updated_at->gt($ticket->created_at))
                <time itemprop="dateModified" datetime="{{ $ticket->updated_at->toIso8601String() }}">
                    {{ __('Updated :time', ['time' => $ticket->updated_at->diffForHumans()]) }}
                </time>
            @endif
        </div>
    </header>

    {{-- Activity feed for freshness --}}
    <section aria-labelledby="updates-heading">
        <h2 id="updates-heading">{{ __('Updates & Activity') }}</h2>

        @foreach($ticket->activities as $activity)
            <article class="activity-item">
                <time datetime="{{ $activity->created_at->toIso8601String() }}">
                    {{ $activity->created_at->diffForHumans() }}
                </time>
                <p>{{ $activity->description }}</p>
            </article>
        @endforeach
    </section>
</article>
```

#### Auto-Updating Homepage Stats

```blade
{{-- Homepage with live stats --}}
<section class="stats-section">
    <h2>{{ __('Platform Statistics') }}</h2>

    <div class="stats-grid" x-data="liveStats">
        <div class="stat">
            <strong x-text="stats.active">{{ $stats['active'] }}</strong>
            <span>{{ __('Active Reports') }}</span>
        </div>
        <div class="stat">
            <strong x-text="stats.resolved_today">{{ $stats['resolved_today'] }}</strong>
            <span>{{ __('Resolved Today') }}</span>
        </div>
    </div>
</section>

@push('scripts')
<script>
Alpine.data('liveStats', () => ({
    stats: @json($stats),

    init() {
        // Update stats every 5 minutes
        setInterval(() => {
            fetch('/api/stats')
                .then(res => res.json())
                .then(data => this.stats = data);
        }, 300000);
    }
}));
</script>
@endpush
```

---

## Implementation Timeline

### Phase 1: Technical Foundation (Week 1-2)
- [ ] Optimize all images to WebP
- [ ] Implement responsive image loading
- [ ] Add resource hints (preconnect, dns-prefetch)
- [ ] Set up service worker caching
- [ ] Fix Core Web Vitals issues

### Phase 2: SEO Meta & Structured Data (Week 3-4)
- [ ] Add comprehensive meta tags to all pages
- [ ] Implement Schema.org structured data
- [ ] Create category landing pages
- [ ] Add breadcrumb navigation
- [ ] Implement hreflang tags

### Phase 3: Content & Performance (Week 5-6)
- [ ] Create SEO-optimized guide pages
- [ ] Implement code splitting
- [ ] Add lazy loading for non-critical components
- [ ] Optimize JavaScript execution
- [ ] Reduce unused CSS/JS

### Phase 4: Monitoring & Optimization (Ongoing)
- [ ] Set up Google Search Console
- [ ] Monitor Core Web Vitals
- [ ] Track keyword rankings
- [ ] A/B test page titles/descriptions
- [ ] Analyze user engagement metrics

---

## Testing Checklist

### Performance
- [ ] Lighthouse score > 90 for all pages
- [ ] LCP < 2.5s
- [ ] INP < 200ms
- [ ] CLS < 0.1
- [ ] Total page size < 1MB

### SEO
- [ ] All pages have unique titles
- [ ] Meta descriptions < 160 chars
- [ ] Structured data validates in Google's tool
- [ ] Images have alt text
- [ ] Internal linking implemented
- [ ] Sitemap.xml generated
- [ ] Robots.txt configured

### Mobile
- [ ] Mobile-friendly test passes
- [ ] Touch targets > 48x48px
- [ ] Text readable without zoom
- [ ] No horizontal scrolling
- [ ] Fast mobile load time

---

**Document Version**: 1.0
**Next Review**: January 2026
