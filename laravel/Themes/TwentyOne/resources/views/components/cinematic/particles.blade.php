{{--
  Cinematic Particles Background
  Usage: <x-cinematic.particles :count="20" />
--}}
@props(['count' => 20, 'color' => 'emerald', 'size' => '4px'])

@php
    $colors = [
        'emerald' => 'rgba(16, 185, 129, 0.6)',
        'blue' => 'rgba(59, 130, 246, 0.6)',
        'purple' => 'rgba(139, 92, 246, 0.6)',
    ];
    $particleColor = $colors[$color] ?? $colors['emerald'];
@endphp

<div class="cinematic-particles absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
    @for($i = 0; $i < $count; $i++)
        <div class="cinematic-particle" style="
            --particle-left: {{ rand(0, 100) }}%;
            --particle-delay: {{ $i * 0.5 }}s;
            --particle-duration: {{ rand(10, 20) }}s;
            --particle-color: {{ $particleColor }};
            --particle-size: {{ $size }};
        "></div>
    @endfor
</div>

@push('styles')
<style>
.cinematic-particles {
    z-index: 1;
}

.cinematic-particle {
    position: absolute;
    width: var(--particle-size);
    height: var(--particle-size);
    background: var(--particle-color);
    border-radius: 50%;
    left: var(--particle-left);
    top: 100%;
    box-shadow: 0 0 20px var(--particle-color);
    animation: particle-float var(--particle-duration) linear infinite;
    animation-delay: var(--particle-delay);
    will-change: transform, opacity;
}

@keyframes particle-float {
    0% {
        transform: translateY(0) scale(0);
        opacity: 0;
    }
    10% {
        opacity: 1;
        transform: scale(1);
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh) scale(0.5);
        opacity: 0;
    }
}

/* Mobile: fewer particles for performance */
@media (max-width: 768px) {
    .cinematic-particle:nth-child(n+11) {
        display: none;
    }
}
</style>
@endpush
