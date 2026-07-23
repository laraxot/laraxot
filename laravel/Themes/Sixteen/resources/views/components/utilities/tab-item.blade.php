@props([
    'id' => null,
    'title' => '',
    'icon' => null,
    'active' => false,
    'disabled' => false,
])

@php
    $tabId = $id ?? 'tab-' . uniqid();
    $activeClass = $active ? 'active' : '';
    $disabledClass = $disabled ? 'disabled' : '';
    $disabledAttr = $disabled ? 'disabled' : '';
@endphp

<li class="nav-item" role="presentation">
    <a class="nav-link {{ $activeClass }} {{ $disabledClass }}" 
       id="{{ $tabId }}-tab" 
       data-bs-toggle="tab" 
       href="#{{ $tabId }}" 
       role="tab" 
       aria-controls="{{ $tabId }}" 
       aria-selected="{{ $active ? 'true' : 'false' }}" 
       {{ $disabledAttr }}>
        @if($icon)
            <span class="tab-icon">
                <x-filament::icon 
                    :icon="$icon" 
                    class="w-5 h-5 mr-2" 
                    aria-hidden="true" 
                />
            </span>
        @endif
        <span class="tab-title">{{ $title }}</span>
    </a>
</li>

<div class="tab-pane fade {{ $active ? 'show active' : '' }}" 
     id="{{ $tabId }}" 
     role="tabpanel" 
     aria-labelledby="{{ $tabId }}-tab">
    <div class="tab-pane-content">
        {{ $slot }}
    </div>
</div>
