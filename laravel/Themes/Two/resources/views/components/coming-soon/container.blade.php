@props([
    'class' => '',
    'showProgress' => true,
    'showSocial' => true
])

@php
$containerClasses = implode(' ', [
    'glass-card',
    'rounded-3xl',
    'shadow-2xl',
    'p-12 md:p-16',
    'max-w-2xl',
    'mx-auto',
    'text-center',
    'relative',
    'overflow-hidden',
    $class
]);
@endphp

<div class="{{ $containerClasses }}">
    <!-- Background Decorative Element -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-light/20 to-primary-dark/20 rounded-full blur-3xl -translate-y-16 translate-x-16"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-primary-dark/20 to-primary-light/20 rounded-full blur-2xl translate-y-12 -translate-x-12"></div>
    
    <!-- Content Container -->
    <div class="relative z-10">
        {{ $slot }}
        
        @if($showProgress)
            <x-coming-soon.progress-bar />
        @endif
        
        @if($showSocial)
            <x-coming-soon.social-links />
        @endif
    </div>
</div>