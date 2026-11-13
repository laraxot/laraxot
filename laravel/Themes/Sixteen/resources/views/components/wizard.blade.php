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

    {{--
        Navigazione di ripiego dei passi non finali (DCP-33/91): primo passo = solo "Avanti" (`btn btn-primary mobile-full`), poi Indietro / Avanti
        (`cmp-nav-steps`) come nel riferimento; niente "Salva richiesta" (`save()` qui crea il ticket). Si nasconde da sola se la vista del passo ha la
        propria navigazione (`[data-wizard-nav]`, overrides.css). L'ultimo passo ha la sua (wizard/summary-step). Testi dal JSON della pagina (`nav.*`).
    --}}
    @php
        $navData = (array) data_get($this->blockData ?? [], 'nav', []);
        $navIcon = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg';
    @endphp
    <div x-cloak class="dc-wizard-footer" x-show="! isLastStep()">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 pb-40 pb-lg-80">
                <div x-show="isFirstStep()">
                    <button type="button" class="btn btn-primary mobile-full" x-on:click="requestNextStep()"><span class="">{{ $navData['next_step'] ?? '' }}</span></button>
                </div>
                <div x-show="! isFirstStep()" class="cmp-nav-steps">
                    <nav class="steppers-nav" aria-label="{{ $navData['aria_label'] ?? '' }}">
                        <button type="button" class="btn btn-sm steppers-btn-prev p-0" x-on:click="goToPreviousStep">
                            <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $navIcon }}#it-chevron-left"></use></svg>
                            <span class="text-button-sm t-primary">{{ $navData['back'] ?? '' }}</span>
                        </button>
                        <button type="button" class="btn btn-primary btn-sm steppers-btn-confirm" x-on:click="requestNextStep()">
                            <span class="text-button-sm">{{ $navData['next_step'] ?? '' }}</span>
                            <svg class="icon icon-white icon-sm" aria-hidden="true"><use href="{{ $navIcon }}#it-chevron-right"></use></svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Azioni canoniche di Filament: restano nel DOM (submit, previous, next) ma non sono mostrate; la navigazione visibile e' quella sopra o della vista del passo. --}}
    <div x-cloak hidden class="fi-sc-wizard-footer" x-show="false" aria-hidden="true">
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
