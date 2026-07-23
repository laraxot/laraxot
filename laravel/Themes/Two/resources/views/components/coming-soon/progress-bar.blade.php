@props([
    'animated' => true,
    'class' => ''
])

@php
$containerClasses = implode(' ', [
    'bg-gray-100',
    'rounded-full',
    'h-3',
    'my-8',
    'overflow-hidden',
    'relative',
    $class
]);
@endphp

<div class="{{ $containerClasses }}">
    <div class="bg-gradient-primary h-full rounded-full relative overflow-hidden progress-animate">
        <!-- Animated Shine Effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full animate-shine"></div>
        <!-- Progress Indicator -->
        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-2 h-2 bg-white rounded-full shadow-sm"></div>
    </div>
</div>

<style>
@keyframes shine {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.animate-shine {
    animation: shine 2s ease-in-out infinite;
}

.progress-animate {
    animation: progress 3s ease-in-out infinite;
}

@keyframes progress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
}
</style>