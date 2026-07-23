@props(['data' => []])

{{-- Steps Rating Component - Bootstrap Italia Exact Replica --}}
@php
    $currentStep = $data['currentStep'] ?? 1;
    $totalSteps = $data['totalSteps'] ?? 5;
    $stepLabels = $data['stepLabels'] ?? [
        'Ufficio',
        'Data/Ora', 
        'Dettagli',
        'Richiedente',
        'Conferma'
    ];
@endphp

<div class="cmp-steps-rating">
    <div class="progress-steps-wrapper">
        <ol class="progress-steps">
            @for($i = 1; $i <= $totalSteps; $i++)
            <li class="step {{ $i <= $currentStep ? 'active' : '' }} {{ $i == $currentStep ? 'current' : '' }}">
                <span class="step-number">{{ $i }}</span>
                <span class="step-label">{{ $stepLabels[$i - 1] ?? 'Step ' . $i }}</span>
            </li>
            @endfor
        </ol>
    </div>
</div>
