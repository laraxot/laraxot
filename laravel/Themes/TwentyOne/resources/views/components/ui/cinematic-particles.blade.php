@props(['count' => 50, 'speed' => 'normal'])

@php
    $speeds = [
        'slow' => ['min' => 20, 'max' => 30],
        'normal' => ['min' => 12, 'max' => 24],
        'fast' => ['min' => 8, 'max' => 16],
    ];
    $speedRange = $speeds[$speed] ?? $speeds['normal'];
@endphp

<div class="cinematic-particles fixed inset-0 pointer-events-none z-0 overflow-hidden" 
     aria-hidden="true" 
     role="presentation">
    @for ($i = 0; $i < $count; $i++)
        @php
            $layer = $i % 3;
            $layerClass = match($layer) {
                0 => 'particle--bg',
                1 => 'particle--mid',
                default => 'particle--fg',
            };
            $delay = $i * 0.5;
            $duration = $speedRange['min'] + ($i % 4) * 4;
            $left = ($i * 73) % 100;
            $animationDelay = -$delay;
        @endphp
        
        <div class="particle {{ $layerClass }}"
             style="--delay: {{ $delay }}s; 
                    --duration: {{ $duration }}s;
                    left: {{ $left }}%;
                    animation-delay: {{ $animationDelay }}s;">
        </div>
    @endfor
</div>
