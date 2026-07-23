@php
use Illuminate\Support\Facades\Route;

// Build breadcrumb based on current route
$breadcrumbs = [];
$currentPath = Route::current() ? Route::current()->uri() : '';
$locale = app()->getLocale();

// Home is always first
$breadcrumbs[] = [
    'label' => 'Home',
    'url' => route('home')
];

// Determine current page and add appropriate breadcrumbs
if (str_contains($currentPath, 'services')) {
    $breadcrumbs[] = ['label' => 'Servizi', 'url' => '/services'];
    if (str_contains($currentPath, 'services/')) {
        // Service detail page - would extract slug from route parameter
        $breadcrumbs[] = ['label' => 'Dettaglio Servizio', 'url' => null];
    }
} elseif (str_contains($currentPath, 'about')) {
    $breadcrumbs[] = ['label' => 'Chi Siamo', 'url' => '/about'];
} elseif (str_contains($currentPath, 'blog')) {
    $breadcrumbs[] = ['label' => 'Blog', 'url' => '/blog'];
    if (str_contains($currentPath, 'blog/')) {
        $breadcrumbs[] = ['label' => 'Articolo', 'url' => null];
    }
} elseif (str_contains($currentPath, 'faq')) {
    $breadcrumbs[] = ['label' => 'FAQ', 'url' => '/faq'];
} elseif (str_contains($currentPath, 'contacts')) {
    $breadcrumbs[] = ['label' => 'Contatti', 'url' => '/contacts'];
} elseif (str_contains($currentPath, 'admin')) {
    $breadcrumbs[] = ['label' => 'Area Admin', 'url' => '/admin'];
}
@endphp

@if (count($breadcrumbs) > 1)
<nav class="bg-gray-50 border-b" aria-label="Breadcrumb">
    <div class="container mx-auto px-4 py-3">
        <ol class="flex items-center space-x-2 text-sm">
            @foreach ($breadcrumbs as $index => $breadcrumb)
                @if ($index === count($breadcrumbs) - 1)
                    <li class="text-gray-700 font-medium" aria-current="page">
                        {{ $breadcrumb['label'] }}
                    </li>
                @else
                    <li>
                        <a href="{{ $breadcrumb['url'] }}" 
                           class="text-gray-500 hover:text-blue-600 transition-colors flex items-center">
                            {{ $breadcrumb['label'] }}
                        </a>
                    </li>
                    <li class="text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</nav>
@endif