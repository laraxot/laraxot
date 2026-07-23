{{--
Bootstrap Italia Timeline Component
Vertical timeline for displaying chronological events with Bootstrap Italia styling
--}}

@props([
    'items' => [],
    'variant' => 'default', // default, compact, alternate
    'theme' => 'light', // light, dark
    'showIcons' => true,
    'showDates' => true
])

@php
    $timelineClasses = [
        'timeline',
        'timeline-' . $variant,
        'timeline-' . $theme
    ];
@endphp

<div {{ $attributes->merge(['class' => implode(' ', $timelineClasses)]) }}>
    @foreach($items as $index => $item)
        @php
            $isEven = ($index % 2) === 0;
            $itemClasses = [
                'timeline-item',
                $variant === 'alternate' && $isEven ? 'timeline-item-end' : '',
                $item['type'] ?? ''
            ];
        @endphp
        
        <div class="{{ implode(' ', array_filter($itemClasses)) }}">
            {{-- Timeline Marker --}}
            <div class="timeline-marker">
                @if($showIcons && isset($item['icon']))
                    <div class="timeline-icon bg-{{ $item['color'] ?? 'primary' }}">
                        <svg class="icon icon-sm text-white">
                            <use href="#it-{{ $item['icon'] }}"></use>
                        </svg>
                    </div>
                @else
                    <div class="timeline-dot bg-{{ $item['color'] ?? 'primary' }}"></div>
                @endif
            </div>

            {{-- Timeline Content --}}
            <div class="timeline-content">
                {{-- Date Badge --}}
                @if($showDates && isset($item['date']))
                    <div class="timeline-date">
                        <x-badge 
                            :color="$item['color'] ?? 'secondary'" 
                            size="sm"
                        >
                            @if(is_string($item['date']))
                                {{ $item['date'] }}
                            @else
                                {{ $item['date']->format('d M Y') }}
                            @endif
                        </x-badge>
                    </div>
                @endif

                {{-- Content Card --}}
                <div class="timeline-card">
                    @if(isset($item['title']))
                        <div class="timeline-header">
                            <h5 class="timeline-title mb-1">{{ $item['title'] }}</h5>
                            @if(isset($item['subtitle']))
                                <p class="timeline-subtitle text-muted mb-0">{{ $item['subtitle'] }}</p>
                            @endif
                        </div>
                    @endif

                    @if(isset($item['content']))
                        <div class="timeline-body">
                            @if(is_string($item['content']))
                                <p class="mb-0">{{ $item['content'] }}</p>
                            @else
                                {!! $item['content'] !!}
                            @endif
                        </div>
                    @endif

                    {{-- Actions/Links --}}
                    @if(isset($item['actions']) && is_array($item['actions']))
                        <div class="timeline-actions mt-3">
                            @foreach($item['actions'] as $action)
                                <a 
                                    href="{{ $action['url'] ?? '#' }}" 
                                    class="btn btn-sm btn-outline-{{ $action['color'] ?? 'primary' }} me-2"
                                    @if(isset($action['target'])) target="{{ $action['target'] }}" @endif
                                >
                                    @if(isset($action['icon']))
                                        <svg class="icon icon-xs me-1">
                                            <use href="#it-{{ $action['icon'] }}"></use>
                                        </svg>
                                    @endif
                                    {{ $action['label'] }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Meta Information --}}
                    @if(isset($item['meta']) && is_array($item['meta']))
                        <div class="timeline-meta mt-2">
                            @foreach($item['meta'] as $metaKey => $metaValue)
                                <small class="text-muted me-3">
                                    <strong>{{ ucfirst($metaKey) }}:</strong> {{ $metaValue }}
                                </small>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 40px;
    width: 2px;
    background: #dee2e6;
    z-index: 1;
}

.timeline-alternate::before {
    left: 50%;
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 80px;
}

.timeline-alternate .timeline-item {
    width: 50%;
    padding-left: 0;
    padding-right: 80px;
}

.timeline-alternate .timeline-item-end {
    left: 50%;
    padding-left: 80px;
    padding-right: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.timeline-alternate .timeline-marker {
    left: calc(50% - 25px);
}

.timeline-alternate .timeline-item-end .timeline-marker {
    right: calc(50% - 25px);
    left: auto;
}

.timeline-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.timeline-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.timeline-content {
    position: relative;
}

.timeline-date {
    margin-bottom: 0.5rem;
}

.timeline-alternate .timeline-date {
    text-align: left;
}

.timeline-alternate .timeline-item-end .timeline-date {
    text-align: right;
}

.timeline-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    position: relative;
    transition: all 0.3s ease;
}

.timeline-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.timeline-card::before {
    content: '';
    position: absolute;
    top: 20px;
    left: -8px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 8px 8px 8px 0;
    border-color: transparent #dee2e6 transparent transparent;
}

.timeline-card::after {
    content: '';
    position: absolute;
    top: 21px;
    left: -6px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 7px 7px 7px 0;
    border-color: transparent white transparent transparent;
}

.timeline-alternate .timeline-item-end .timeline-card::before {
    left: auto;
    right: -8px;
    border-width: 8px 0 8px 8px;
    border-color: transparent transparent transparent #dee2e6;
}

.timeline-alternate .timeline-item-end .timeline-card::after {
    left: auto;
    right: -6px;
    border-width: 7px 0 7px 7px;
    border-color: transparent transparent transparent white;
}

.timeline-title {
    color: #0066cc;
    font-weight: 600;
    font-size: 1.1rem;
}

.timeline-subtitle {
    font-size: 0.9rem;
    font-weight: 500;
}

.timeline-actions .btn {
    font-size: 0.875rem;
}

.timeline-meta {
    border-top: 1px solid #f8f9fa;
    padding-top: 0.75rem;
}

/* Compact variant */
.timeline-compact .timeline-item {
    margin-bottom: 1rem;
    padding-left: 60px;
}

.timeline-compact .timeline-icon {
    width: 30px;
    height: 30px;
}

.timeline-compact .timeline-dot {
    width: 12px;
    height: 12px;
}

.timeline-compact .timeline-marker {
    left: 15px;
}

.timeline-compact::before {
    left: 30px;
}

.timeline-compact .timeline-card {
    padding: 1rem;
}

/* Dark theme */
.timeline-dark .timeline-card {
    background: #343a40;
    border-color: #495057;
    color: white;
}

.timeline-dark .timeline-card::after {
    border-right-color: #343a40;
}

.timeline-dark .timeline-alternate .timeline-item-end .timeline-card::after {
    border-left-color: #343a40;
    border-right-color: transparent;
}

.timeline-dark .timeline-title {
    color: #66a3ff;
}

.timeline-dark .timeline-meta {
    border-top-color: #495057;
}

/* Responsive design */
@media (max-width: 768px) {
    .timeline-alternate {
        padding-left: 0;
    }
    
    .timeline-alternate::before {
        left: 40px;
        transform: none;
    }
    
    .timeline-alternate .timeline-item {
        width: 100%;
        padding-left: 80px;
        padding-right: 0;
        left: 0;
    }
    
    .timeline-alternate .timeline-marker {
        left: 0;
    }
    
    .timeline-alternate .timeline-item .timeline-card::before {
        left: -8px;
        right: auto;
        border-width: 8px 8px 8px 0;
        border-color: transparent #dee2e6 transparent transparent;
    }
    
    .timeline-alternate .timeline-item .timeline-card::after {
        left: -6px;
        right: auto;
        border-width: 7px 7px 7px 0;
        border-color: transparent white transparent transparent;
    }
    
    .timeline-alternate .timeline-date {
        text-align: left !important;
    }
}

/* Animation effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.timeline-item {
    animation: fadeInUp 0.6s ease-out;
}

.timeline-item:nth-child(2) { animation-delay: 0.1s; }
.timeline-item:nth-child(3) { animation-delay: 0.2s; }
.timeline-item:nth-child(4) { animation-delay: 0.3s; }
.timeline-item:nth-child(5) { animation-delay: 0.4s; }
.timeline-item:nth-child(n+6) { animation-delay: 0.5s; }
</style>
@endpush
