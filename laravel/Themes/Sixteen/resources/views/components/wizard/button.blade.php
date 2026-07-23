@props([
    'action' => null,
])

@php
    $type = $action === 'submit' ? 'submit' : 'button';
@endphp

<button {{ $attributes->merge(['type' => $type]) }}>
    {{ $slot }}
</button>

