@props([
    'id' => null,
    'label' => '',
    'disabled' => false,
    'icon' => null,
    'iconSize' => 'sm',
])

@php
    $id = $id ?? 'tab-' . uniqid();
    $iconClass = $iconSize ? 'icon-' . $iconSize : '';
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
        @if($icon)
            <svg class="icon {{ $iconClass }}" aria-hidden="true">
                <use href="#{{ $icon }}"></use>
            </svg>
        @endif
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
