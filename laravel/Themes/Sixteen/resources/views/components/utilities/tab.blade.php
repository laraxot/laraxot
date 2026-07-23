@props([
    'id' => null,
    'label' => '',
    'disabled' => false,
])

@php
    $id = $id ?? 'tab-' . uniqid();
@endphp

<li class="nav-item" role="presentation">
    <button 
        class="nav-link {{ $disabled ? 'disabled' : '' }}"
        id="{{ $id }}-tab"
        data-bs-toggle="tab"
        data-bs-target="#{{ $id }}"
        type="button"
        role="tab"
        aria-controls="{{ $id }}"
        aria-selected="false"
        {{ $disabled ? 'disabled' : '' }}
    >
        {{ $label }}
    </button>
</li>

<div 
    id="{{ $id }}" 
    class="tab-pane fade" 
    role="tabpanel" 
    aria-labelledby="{{ $id }}-tab"
>
    {{ $slot }}
</div>
