{{--
    Status Badge Component
    Based on Design Comuni Italiani badge pattern
    AGID Compliant
--}}

@props(['status'])

@php
$badges = [
    'open' => [
        'class' => 'badge-danger',
        'label' => 'Aperto',
        'icon' => 'it-horn'
    ],
    'in_progress' => [
        'class' => 'badge-warning',
        'label' => 'In Corso',
        'icon' => 'it-settings'
    ],
    'resolved' => [
        'class' => 'badge-success',
        'label' => 'Risolto',
        'icon' => 'it-check'
    ],
    'closed' => [
        'class' => 'badge-secondary',
        'label' => 'Chiuso',
        'icon' => 'it-close'
    ],
];

$badge = $badges[$status] ?? [
    'class' => 'badge-secondary',
    'label' => ucfirst($status),
    'icon' => 'it-info-circle'
];
@endphp

<span class="badge {{ $badge['class'] }}" {{ $attributes }}>
    <svg class="icon icon-white icon-sm">
        <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/svg/sprites.svg#{{ $badge['icon'] }}"></use>
    </svg>
    {{ $badge['label'] }}
</span>
