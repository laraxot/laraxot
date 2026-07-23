@props([
    'class' => 'h-8 w-auto',
    'color' => 'currentColor',
])

@php
    $logoSrc = null;
    $logoAlt = config('app.name');
    try {
        $metatag = \Modules\Xot\Datas\MetatagData::make();
        $logoSrc = $metatag->getBrandLogo();
        if (filled($metatag->logo_alt)) {
            $logoAlt = $metatag->logo_alt;
        }
    } catch (Throwable $e) {
        $logoSrc = null;
    }
@endphp

@if (filled($logoSrc))
    <img
        src="{{ $logoSrc }}"
        alt="{{ $logoAlt }}"
        @if ($color === 'white') style="filter: brightness(0) invert(1);" @endif
        {{ $attributes->merge(['class' => $class]) }}
    />
@else
    <svg
        {{ $attributes->merge(['class' => $class]) }}
        fill="{{ $color }}"
        viewBox="0 0 200 60"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
    >
        <text x="10" y="40" font-family="Arial, sans-serif" font-weight="bold" font-size="32" fill="{{ $color }}">
            {{ config('app.name', 'TechPlanner') }}
        </text>
        <circle cx="170" cy="30" r="20" fill="{{ $color }}" opacity="0.2"/>
        <circle cx="170" cy="30" r="12" fill="{{ $color }}" opacity="0.4"/>
    </svg>
@endif
