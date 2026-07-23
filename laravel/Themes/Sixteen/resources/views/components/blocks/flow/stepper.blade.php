@props(['data' => []])

@php
    $title = (string) ($data['title'] ?? '');
    $description = (string) ($data['description'] ?? '');
    $steps = (array) ($data['steps'] ?? []);
    $currentStep = (int) ($data['currentStep'] ?? 1);
    $navigable = (bool) ($data['navigable'] ?? false);
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
@endphp

<div class="cmp-heading pb-3 pb-lg-4">
    <div class="categoryicon-top d-flex">
        <svg class="icon icon-primary mr-10 icon-md" aria-hidden="true">
            <use href="{{ $sprite }}#it-warning"></use>
        </svg>
        <h1 class="title-xxxlarge">{{ $title }}</h1>
    </div>
    @if ($description !== '')
        <p class="subtitle-small">{{ $description }}</p>
    @endif
</div>

@if ($steps !== [])
<div class="col-12">
    <div class="steppers">
        <div class="steppers-header">
            <ul>
                @foreach ($steps as $index => $step)
                    @php
                        $stepNum = $index + 1;
                        $isCompleted = $stepNum < $currentStep;
                        $isActive = $stepNum === $currentStep;
                        $stepClass = $isCompleted ? 'confirmed' : ($isActive ? 'active' : '');
                    @endphp
                    <li class="{{ $stepClass }}">
                        @if ($isCompleted)
                            <svg class="icon steppers-success" aria-hidden="true">
                                <use href="{{ $sprite }}#it-check"></use>
                            </svg>
                        @else
                            <span class="steppers-number">{{ $stepNum }}</span>
                        @endif
                        <span class="visually-hidden">
                            {{ $isCompleted ? __('fixcity::segnalazione.steps.confirmed.label') : ($isActive ? __('fixcity::segnalazione.steps.active.label') : __('fixcity::segnalazione.steps.step_number.label', ['number' => $stepNum])) }}: {{ $step['title'] ?? '' }}
                        </span>
                    </li>
                @endforeach
            </ul>
            <span class="steppers-index">
                {{ $currentStep }}/{{ count($steps) }}
            </span>
        </div>
    </div>
</div>
<p class="title-xsmall d-lg-none my-5">
    {{ __('fixcity::segnalazione.steps.current_of_total.label', ['current' => $currentStep, 'total' => count($steps)]) }}: {{ $steps[$currentStep - 1]['title'] ?? '' }}
</p>
@endif
