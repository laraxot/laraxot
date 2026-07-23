@props([
    'steps' => [],
    'totalSteps' => 0,
    'isFirstStep' => true,
    'isLastStep' => false,
    'hasSave' => false,
])

@php
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="cmp-nav-steps">
    <nav class="steppers-nav" aria-label="Step">
        <button
            type="button"
            class="btn btn-sm steppers-btn-prev"
            x-bind:class="{ 'btn-back-step': !isFirstStep() }"
            x-on:click="goToPreviousStep"
            x-show="!isFirstStep()"
            :disabled="isFirstStep()"
            aria-label="Previous step"
        >
            <svg class="icon icon-primary icon-sm" aria-hidden="true">
                <use href="{{ $sprite }}#it-chevron-left"></use>
            </svg>
            <span class="text-button-sm t-primary">Indietro</span>
        </button>

        <button
            type="button"
            class="btn btn-outline-primary bg-white btn-sm steppers-btn-save d-none d-lg-block"
            x-show="!isFirstStep() && !isLastStep()"
            x-on:click="$wire.save()"
        >
            <span class="text-button-sm t-primary">Salva richiesta</span>
        </button>

        <button
            type="button"
            class="btn btn-outline-primary bg-white btn-sm steppers-btn-save d-block d-lg-none"
            x-show="!isFirstStep() && !isLastStep()"
            x-on:click="$wire.save()"
        >
            <span class="text-button-sm t-primary">Salva</span>
        </button>

        <button
            type="button"
            class="btn btn-primary btn-sm steppers-btn-confirm"
            x-bind:class="{ 'btn-next-step': !isLastStep() }"
            x-on:click="isLastStep() ? $wire.save() : requestNextStep()"
        >
            <span class="text-button-sm" x-text="isLastStep() ? 'Invia' : 'Avanti'"></span>
            <svg class="icon icon-white icon-sm" aria-hidden="true" x-show="!isLastStep()">
                <use href="{{ $sprite }}#it-chevron-right"></use>
            </svg>
        </button>
    </nav>
</div>
