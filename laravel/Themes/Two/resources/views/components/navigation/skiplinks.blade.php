@props([
    'showMain' => true,
    'showContent' => true,
    'showFooter' => true,
])

<div class="sr-only focus:not-sr-only focus:outline-none focus:ring-2 focus:ring-primary-500">
    @if($showMain)
        <a href="#main" class="absolute top-4 left-4 z-50 bg-primary-600 text-white px-4 py-2 rounded-md font-medium hover:bg-primary-700 transition-colors">
            {{ __('Vai al contenuto principale') }}
        </a>
    @endif
    
    @if($showContent)
        <a href="#content" class="absolute top-12 left-4 z-50 bg-primary-600 text-white px-4 py-2 rounded-md font-medium hover:bg-primary-700 transition-colors">
            {{ __('Vai al contenuto') }}
        </a>
    @endif
    
    @if($showFooter)
        <a href="#footer" class="absolute top-20 left-4 z-50 bg-primary-600 text-white px-4 py-2 rounded-md font-medium hover:bg-primary-700 transition-colors">
            {{ __('Vai al footer') }}
        </a>
    @endif
</div>

<style>
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

.focus\:not-sr-only:focus {
    position: static;
    width: auto;
    height: auto;
    padding: inherit;
    margin: inherit;
    overflow: visible;
    clip: auto;
    white-space: normal;
}
</style>