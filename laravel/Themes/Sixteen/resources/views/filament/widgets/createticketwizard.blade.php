@php
    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
    $contacts = is_array($blockData['contacts'] ?? null) ? $blockData['contacts'] : [];
@endphp

{{-- Wrapper Design Comuni: titolo + form Filament Wizard (schema in CreateTicketWizardWidget::getFormSchema) --}}
<div class="cmp-wizard-widget">
    <div class="container" id="main-container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="cmp-heading pb-3 pb-lg-4">
                    <h1 class="title-xxxlarge">{{ $pageTitle }}</h1>
                    @if($pageDescription !== '')
                        <p class="text-paragraph mb-0">{{ $pageDescription }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 pb-40 pb-lg-80">
                <x-filament-widgets::widget>
                    <form wire:submit="{{ $this->getFormSubmitAction() }}">
                        @if (isset($errors) && ($errors->has('data.submit') || $errors->has('submit')))
                            <div class="alert alert-danger mb-4" role="alert">
                                {{ $errors->first('data.submit') ?: $errors->first('submit') }}
                            </div>
                        @endif
                        {{ $this->form }}
                    </form>
                    <x-filament-actions::modals />
                </x-filament-widgets::widget>
            </div>
        </div>
    </div>

</div>
