{{--
    Priority Badge Component
    Based on Design Comuni Italiani badge pattern
    AGID Compliant
--}}

@props(['priority'])

@php
$badges = [
    'urgent' => [
        'class' => 'badge-danger',
        'label' => 'Urgente',
        'icon' => 'it-warning-circle'
    ],
    'high' => [
        'class' => 'badge-warning',
        'label' => 'Alta',
        'icon' => 'it-arrow-up-triangle'
    ],
    'medium' => [
        'class' => 'badge-info',
        'label' => 'Media',
        'icon' => 'it-minus-circle'
    ],
    'low' => [
        'class' => 'badge-secondary',
        'label' => 'Bassa',
        'icon' => 'it-arrow-down-triangle'
    ],
];

$badge = $badges[$priority] ?? [
    'class' => 'badge-secondary',
    'label' => ucfirst($priority),
    'icon' => 'it-info-circle'
];
@endphp

<span class="badge {{ $badge['class'] }}" {{ $attributes }}>
    <svg class="icon icon-white icon-sm">
        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/svg/sprites.svg#{{ $badge['icon'] }}"></use>
    </svg>
    {{ $badge['label'] }}
</span>
