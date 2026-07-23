@php
    $steps = $steps ?? [];
    $totalSteps = $totalSteps ?? count($steps);

    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="steppers">
    <div class="steppers-header">
        <ul class="steppers-list" role="list">
            @foreach($steps as $index => $step)
                @php
                    $stepNum = $index + 1;
                    $stepLabel = $step->getLabel();
                    $stepKey = $step->getKey();
                @endphp

                <li
                    class="stepper-step"
                    x-bind:class="{
                        'active': getStepIndex(step) === {{ $index }},
                        'confirmed': getStepIndex(step) > {{ $index }},
                    }"
                    x-bind:aria-current="getStepIndex(step) === {{ $index }} ? 'step' : null"
                >
                    {{ $stepLabel }}
                    <span class="visually-hidden" x-show="getStepIndex(step) === {{ $index }}">
                        ({{ __('fixcity::segnalazione.steps.active.label') }})
                    </span>
                </li>
            @endforeach
        </ul>
        <span
            class="steppers-index"
            aria-hidden="true"
            x-text="`${getStepIndex(step) + 1}/{{ $totalSteps }}`"
        >{{ $totalSteps > 0 ? '1' : '0' }}/{{ $totalSteps }}</span>
    </div>
</div>
