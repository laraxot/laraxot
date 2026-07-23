@php
    $isContained = $isContained();
    $key = $getKey();
    $previousAction = $getAction('previous');
    $nextAction = $getAction('next');
    $steps = $getChildSchema()->getComponents();
    $isHeaderHidden = $isHeaderHidden();
@endphp

<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('wizard', 'filament/schemas') }}"
    x-data="wizardSchemaComponent({
                isSkippable: @js($isSkippable()),
                isStepPersistedInQueryString: @js($isStepPersistedInQueryString()),
                key: @js($key),
                livewireId: @js($this->getId()),
                schemaKey: @js($getRootContainer()->getKey()),
                startStep: @js($getStartStep()),
                stepQueryStringKey: @js($getStepQueryStringKey()),
            })"
    x-on:next-wizard-step.window="if ($event.detail.key === @js($key)) goToNextStep()"
    x-on:go-to-wizard-step.window="$event.detail.key === @js($key) && goToStep($event.detail.step)"
    wire:ignore.self
    {{
        $attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                'wizard-dc-form-shell',
                'fi-sc-wizard',
                'fi-contained' => $isContained,
                'fi-sc-wizard-header-hidden' => $isHeaderHidden,
            ])
    }}
>
    <input
        type="hidden"
        value="{{
            collect($steps)
                ->filter(static fn (\Filament\Schemas\Components\Wizard\Step $step): bool => $step->isVisible())
                ->map(static fn (\Filament\Schemas\Components\Wizard\Step $step): ?string => $step->getKey())
                ->values()
                ->toJson()
        }}"
        x-ref="stepsData"
    />

    @if (! $isHeaderHidden)
        <x-pub_theme::wizard.stepper
            :steps="$steps"
            :total-steps="count($steps)"
        />
    @endif

    @foreach ($steps as $step)
        {{ $step }}
    @endforeach

    <div x-cloak class="fi-sc-wizard-footer">
        <div
            x-cloak
            @if (! $previousAction->isDisabled())
                x-on:click="goToPreviousStep"
            @endif
            x-show="! isFirstStep()"
        >
            {{ $previousAction }}
        </div>

        <div x-show="isFirstStep()">
            {{ $getCancelAction() }}
        </div>

        <div
            x-cloak
            @if (! $nextAction->isDisabled())
                x-on:click="requestNextStep()"
            @endif
            x-bind:class="{ 'fi-hidden': isLastStep() }"
            wire:loading.class="fi-disabled"
        >
            {{ $nextAction }}
        </div>

        <div x-bind:class="{ 'fi-hidden': ! isLastStep() }">
            {{ $getSubmitAction() }}
        </div>
    </div>

</div>
