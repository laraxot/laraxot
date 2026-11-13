{{-- Bootstrap Italia Badge Component --}}

@php
$variantClass = match($variant ?? 'primary') {
    'secondary' => 'badge-secondary',
    'success' => 'badge-success',
    'danger' => 'badge-danger',
    'warning' => 'badge-warning',
    'light' => 'badge-light',
    'dark' => 'badge-dark',
    default => 'badge-primary',
};
@endphp

<span class="badge badge-italia {{ $variantClass }} {{ ($pill ?? false) ? 'rounded-pill' : '' }}">
    {{ $text ?? $slot ?? '' }}
</span>
