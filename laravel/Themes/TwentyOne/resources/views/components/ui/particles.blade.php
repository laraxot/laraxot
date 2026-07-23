@props([
    'count' => 80,
    'color' => 'rgba(16, 185, 129, 0.3)',
    'size' => '2px',
    'zIndex' => 1,
    'variant' => 'antigravity',
])

{{-- Particles Background Effect --}}
<div class="ui-particles ui-particles--{{ $variant }} absolute inset-0 overflow-hidden pointer-events-none" 
     style="z-index: {{ $zIndex }};"
     aria-hidden="true">
    @for($i = 0; $i < $count; $i++)
        @php
            $top = rand(0, 100);
            $left = rand(0, 100);
            $duration = rand(12, 24);
            $delay = rand(0, 18);
            $particleSize = rand(2, 5);
            $opacity = rand(20, 50) / 100;
            $driftX = rand(-36, 36).'px';
            $rise = '-'.rand(120, 260).'px';
        @endphp
        <div class="ui-particle"
             style="
                 --particle-top: {{ $top }}%;
                 --particle-left: {{ $left }}%;
                 --particle-size: {{ is_numeric($size) ? $size . 'px' : $size }};
                 --particle-base-size: {{ $particleSize }}px;
                 --particle-color: {{ $color }};
                 --particle-opacity: {{ $opacity }};
                 --particle-duration: {{ $duration }}s;
                 --particle-delay: {{ $delay }}s;
                 --particle-drift-x: {{ $driftX }};
                 --particle-rise: {{ $rise }};
                 width: var(--particle-base-size);
                 height: var(--particle-base-size);
             ">
        </div>
    @endfor
</div>
