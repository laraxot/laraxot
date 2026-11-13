{{--
    Barra dei passi del wizard con il markup di sito/segnalazione-0N (classi `steppers`, `confirmed`, `active`, icona di conferma): lo stato
    viene da Alpine (`getStepIndex(step)` del wizard Filament), quindi `<template x-if>` crea l'icona e il testo per lo screen reader solo dove servono,
    come nel riferimento. Etichette dai lang `fixcity::ticket.steps.*`.
--}}
@php
    $steps = $steps ?? [];
    $totalSteps = $totalSteps ?? count($steps);

    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="steppers">
    <div class="steppers-header">
        <ul>
            @foreach($steps as $index => $step)
                <li
                    x-bind:class="{
                        'active': getStepIndex(step) === {{ $index }},
                        'confirmed': getStepIndex(step) > {{ $index }},
                    }"
                    x-bind:aria-current="getStepIndex(step) === {{ $index }} ? 'step' : null"
                > {{ $step->getLabel() }}
                    <template x-if="getStepIndex(step) > {{ $index }}"><svg class="icon steppers-success" aria-hidden="true"><use href="{{ $sprite }}#it-check"></use></svg></template>
                    <template x-if="getStepIndex(step) > {{ $index }}"><span class="visually-hidden">{{ __('fixcity::ticket.steps.confirmed.label') }}</span></template>
                    <template x-if="getStepIndex(step) === {{ $index }}"><span class="visually-hidden">{{ __('fixcity::ticket.steps.active.label') }}</span></template>
                </li>
            @endforeach
        </ul>
        <span class="steppers-index" aria-hidden="true" x-text="`${getStepIndex(step) + 1}/{{ $totalSteps }}`">{{ $totalSteps > 0 ? '1' : '0' }}/{{ $totalSteps }}</span>
    </div>
</div>
