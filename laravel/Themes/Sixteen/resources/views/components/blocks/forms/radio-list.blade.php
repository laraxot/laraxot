@props(['data' => []])

{{-- Radio List Component - Bootstrap Italia Exact Replica --}}
@php
    $title = $data['title'] ?? 'Seleziona un\'opzione';
    $options = $data['options'] ?? [];
    $name = $data['name'] ?? 'radio-list';
@endphp

<div class="cmp-radio-list">
    <div class="card card-teaser shadow p-4 rounded border border-light">
        <div class="card-body">
            @if($title)
            <h5 class="card-title mb-3">{{ $title }}</h5>
            @endif
            
            <div class="radio-list-wrapper">
                @foreach($options as $index => $option)
                <div class="checkbox-body {{ $index > 0 ? 'mt-3' : '' }}">
                    <input type="radio" 
                           name="{{ $name }}" 
                           id="{{ $name }}-{{ $index }}" 
                           value="{{ $option['value'] ?? $index }}"
                           class="custom-control-input">
                    <label for="{{ $name }}-{{ $index }}" class="custom-control-label">
                        <span class="text">{{ $option['label'] ?? 'Opzione ' . ($index + 1) }}</span>
                        @if($option['description'] ?? false)
                        <span class="description d-block text-muted small">{{ $option['description'] }}</span>
                        @endif
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
